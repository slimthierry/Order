<?php

namespace App\Services;

use App\Services\Contracts\IShipsDataProvider;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\Event;
use App\Models\HafasTrip;

use App\Models\PolyLine;
use App\Models\Status;
use App\Models\TrainCheckin;
use App\Models\TrainStations;
use App\Notifications\TwitterNotSent;
use App\Notifications\UserJoinedConnection;
use App\Traits\DataProvider;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ShipsDataProvider implements IShipsDataProvider
{
    use DataProvider;

    public function __construct()
    {

    }

    /**
     * Takes just about any date string and formats it in Y-m-d H:i:s which is
     * the required format for MySQL inserts.
     * @return String
     */

    public static function dateToMySQLEscape(String $timeString, $delaySeconds = 0): String
    {
        return date("Y-m-d H:i:s", strtotime($timeString) - $delaySeconds);
    }

    public static function TrainAutocomplete($station)
    {
        $client   = new Client(['base_uri' => config('trwl.db_rest')]);
        $response = $client->request('GET', "stations?query=$station&fuzzy=true");
        if ($response->getBody()->getContents() <= 2) {
            $response = $client->request('GET', "locations?query=$station");
        }
        $json  = $response->getBody()->getContents();
        $array = json_decode($json, true);
        foreach(array_keys($array) as $key) {
            unset($array[$key]['type']);
            unset($array[$key]['location']);
            unset($array[$key]['products']);
            $array[$key]['provider'] = 'train';
        }
        return $array;
    }

    public static function BusAutocomplete($station)
    {
        $client   = new Client(['base_uri' => config('trwl.flix_rest')]);
        $response = $client->request('GET', "stations/?query=$station");
        $json     = $response->getBody()->getContents();
        $array    = json_decode($json, true);

        foreach(array_keys($array) as $key) {
            unset($array[$key]['relevance']);
            unset($array[$key]['score']);
            unset($array[$key]['weight']);
            unset($array[$key]['type']);
            $array[$key]['provider'] = 'bus';
        }
        return $array;
    }

    public static function TrainStationboard($station, $when='now', $travelType=null)
    {
        if (empty($station)) {
            return false;
        }
        if ($when === null) {
            $when = strtotime('-5 minutes');
        }
        $ibnrObject = self::TrainAutocomplete($station);
        $departures = self::getTrainDepartures($ibnrObject[0]['id'], $when, $travelType);
        $station    = $ibnrObject[0];

        if (empty($station['name'])) {
            return null;
        }
        return ['station' => $station, 'departures' => $departures, 'when' => $when];
    }

    public static function FastTripAccess($departure, $lineName, $number, $when)
    {
        $departuresArray = self::getTrainDepartures($departure, $when);
        foreach ($departuresArray as $departure) {
            if ($departure->line->name === $lineName && $departure->line->fahrtNr == $number) {
                return $departure;
            }
        }
        return null;
    }

    public static function StationByCoordinates($latitude, $longitude)
    {
        $client = new Client(['base_uri' => config('trwl.db_rest')]);
        $response = $client->request('GET', "stops/nearby?latitude=$latitude&longitude=$longitude&results=1");
        $json = json_decode($response->getBody()->getContents());

        if (count($json) === 0) {
            return null;
        }

        return $json[0];
    }

    private static function getTrainDepartures($ibnr, $when='now', $trainType=null)
    {
        $client = new Client(['base_uri' => config('trwl.db_rest')]);
        $trainTypes = array(
            'suburban' => 'false',
            'subway' => 'false',
            'tram' => 'false',
            'bus' => 'false',
            'ferry' => 'false',
            'express' => 'false',
            'regional' => 'false',
        );
        $appendix   = '';

        if ($trainType != null) {
            $trainTypes[$trainType] = 'true';
            $appendix               = '&'.http_build_query($trainTypes);
        }
        $response = $client->request('GET', "stations/$ibnr/departures?when=$when&duration=15" . $appendix);
        $json     = json_decode($response->getBody()->getContents());

        //remove express trains in filtered results
        if ($trainType != null && $trainType != 'express') {
            foreach ($json as $key=>$item) {
                if ($item->line->product != $trainType) {
                    unset($json[$key]);
                }
            }
        }
        $json = self::sortByWhenOrScheduledWhen($json);
        return $json;
    }

    // Train with cancelled stops show up in the stationboard sometimes with when == 0.
    // However, they will have a scheduled When. This snippet will sort the departures
    // by actual When or use scheduled When if actual is empty.

    public static function sortByWhenOrScheduledWhen(Array $departuresList): Array
    {
        uasort($departuresList, function($a, $b) {
            $dateA = $a->when;
            if($dateA == null) {
                $dateA = $a->scheduledWhen;
            }

            $dateB = $b->when;
            if($dateB == null) {
                $dateB = $b->scheduledWhen;
            }

            return ($dateA < $dateB) ? -1 : 1;
        });

        return $departuresList;
    }

    public static function CalculateTrainPoints($distance, $category, $departure, $arrival, $delay)
    {
        $now      = time();
        $factorDB = DB::table('pointscalculation')
            ->where([
                        ['type', 'train'],
                        ['transport_type', $category
                        ]])
            ->first();

        $factor = 1;
        if ($factorDB != null) {
            $factor = $factorDB->value;
        }
        $arrivalTime   = ( (is_int($arrival)) ? $arrival : strtotime($arrival)) + $delay;
        $departureTime = ( (is_int($departure)) ? $departure : strtotime($departure)) + $delay;
        $points        = $factor + ceil($distance / 10);

        /**
         * Full points, 20min before the departure time or during the ride
         *   D-20         D                      A
         *    |           |                      |
         * -----------------------------------------> t
         *     xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
         */
        // print_r([$departureTime - 20*60 < $now, $now < $arrivalTime]);
        if (($departureTime - 20*60) < $now && $now < $arrivalTime) {
            return $points;
        }

        /**
         * Reduced points, one hour before departure and after arrival
         *
         *   D-60         D          A          A+60
         *    |           |          |           |
         * -----------------------------------------> t
         *     xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
         */
        if (($departureTime - 60*60) < $now && $now < ($arrivalTime + 60*60)) {
            return ceil($points * 0.25);
        }

        // Else: Just give me one. It's a point for funsies and the minimal amount of points that you can get.
        return 1;
    }

    private static function getHAFAStrip($tripID, $lineName)
    {
        $trip = HafasTrip::where('trip_id', $tripID)->first();

        if ($trip === null) {
            $trip        = new HafasTrip;
            $client      = new Client(['base_uri' => config('trwl.db_rest')]);
            $response    = $client->request('GET', "trips/$tripID?lineName=$lineName&polyline=true");
            $json        = json_decode($response->getBody()->getContents());
            $origin      = self::getTrainStation($json->origin->id,
                                              $json->origin->name,
                                              $json->origin->location->latitude,
                                              $json->origin->location->longitude);
            $destination = self::getTrainStation($json->destination->id,
                                                 $json->destination->name,
                                                 $json->destination->location->latitude,
                                                 $json->destination->location->longitude);
            if ($json->line->name === null) {
                $json->line->name = $json->line->fahrtNr;
            }

            if ($json->line->id === null) {
                $json->line->id = '';
            }
            $polyLineHash = self::getPolylineHash(json_encode($json->polyline));

            $trip->trip_id     = $tripID;
            $trip->category    = $json->line->product;
            $trip->number      = $json->line->id;
            $trip->linename    = $json->line->name;
            $trip->origin      = $origin->ibnr;
            $trip->destination = $destination->ibnr;
            $trip->stopovers   = json_encode($json->stopovers);
            $trip->polyline    = $polyLineHash;
            $trip->departure   = self::dateToMySQLEscape($json->departure ?? $json->scheduledDeparture,
                                                       $json->departureDelay ?? 0);
            $trip->arrival     = self::dateToMySQLEscape($json->arrival ?? $json->scheduledArrival,
                                                     $json->arrivalDelay ?? 0);
            if(isset($json->arrivalDelay)) {
                $trip->delay = $json->arrivalDelay;
            }
            $trip->save();
        }
        return $trip;
    }

    public static function getTrainStation ($ibnr, $name, $latitude, $longitude)
    {
        $station = TrainStations::where('ibnr', $ibnr)->first();
        if ($station === null) {
            $station            = new TrainStations;
            $station->ibnr      = $ibnr;
            $station->name      = $name;
            $station->latitude  = $latitude;
            $station->longitude = $longitude;
            $station->save();
        }
        return $station;
    }

    public static function getPolylineHash($polyline)
    {
        $hash       = md5($polyline);
        $dbPolyline = PolyLine::where('hash', $hash)->first();
        if ($dbPolyline === null) {
            $newPolyline           = new PolyLine;
            $newPolyline->hash     = $hash;
            $newPolyline->polyline = $polyline;
            $newPolyline->save();
        }
        return $hash;
    }

    public static function getLatestArrivals($user)
    {
        return TrainCheckin::with('Status')->whereHas('Status', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function($t) {
            return TrainStations::where("ibnr", $t->destination)->first();
        })->unique()->take(5);
    }

    public static function SetHome($user, $ibnr)
    {
        $client     = new Client(['base_uri' => config('trwl.db_rest')]);
        $response   = $client->request('GET', "locations?query=$ibnr")->getBody()->getContents();
        $ibnrObject = json_decode($response);

        $station = self::getTrainStation(
            $ibnrObject[0]->id,
            $ibnrObject[0]->name,
            $ibnrObject[0]->location->latitude,
            $ibnrObject[0]->location->longitude
        );

        $user->home_id = $station->id;
        try {
            $user->save();
        }
        catch (\Exception $e) {
            return false;
        }
        return $station->name;
    }

    public static function usageByDay(Carbon $date)
    {
        $hafas = HafasTrip::where("created_at", ">=", $date->copy()->startOfDay())
            ->where("created_at", "<=", $date->copy()->endOfDay())
            ->count();

        $returnArray = ["hafas" => $hafas];

        /** Shortcut, wenn eh nichts passiert ist. */
        if($hafas == 0) {
            return $returnArray;
        }

        $polylines                = PolyLine::where("created_at", ">=", $date->copy()->startOfDay())
            ->where("created_at", "<=", $date->copy()->endOfDay())
            ->count();
        $returnArray['polylines'] = $polylines;

        $transportTypes           = [
            'nationalExpress',
            'national',
            'express',
            'regionalExp',
            'regional',
            'suburban',
            'bus',
            'tram',
            'subway',
            'ferry',];

        $seenCheckins = 0;
        for ($i = 0; $seenCheckins < $hafas && $i < count($transportTypes); $i++) {
            $transport = $transportTypes[$i];

             $returnArray[$transport] = HafasTrip::where("created_at", ">=", $date->copy()->startOfDay())
                ->where("created_at", "<=", $date->copy()->endOfDay())
                ->where('category', '=', $transport)
                ->count();
             $seenCheckins += $returnArray[$transport];
        }

        return $returnArray;
    }
}
