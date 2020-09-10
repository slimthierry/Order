<?php

namespace App\Services;

use App\Models\UserMember;
use App\Services\Contracts\IStatusDataProvider;
use App\Traits\DataProvider;
use App\Models\Status;
use App\Notifications\StatusLiked;
use Carbon\Carbon;

use App\Services\LikesDataProvider;


class StatusDataProvider implements IStatusDataProvider
{

    use DataProvider;

    public function __construct(LikesDataProvider $likesDataProvider)
    {
        $this->likesDataProvider = $likesDataProvider;

    }

     /**
     *
     */

    public static function getStatus($statusId)
    {
        return Status::where('id', $statusId)->with('user',
                                                    'trainCheckin',
                                                    'trainCheckin.Origin',
                                                    'trainCheckin.Destination',
                                                    'trainCheckin.HafasTrip',
                                                    'event')->withCount('likes')->firstOrFail();
    }

    /**
     * This Method returns the current active status(es) for all users or a specific user.
     *
     * @param null $userId UserId to get the current active status for a user. Defaults to null.
     * @param bool $array This parameter is a temporary solution until the frontend is no more dependend on blade.
     * @return Status|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getActiveStatuses ($userId = null, bool $array = true)
    {
        if ($userId === null) {
            $statuses = Status::with('user',
                'trainCheckin',
                'trainCheckin.Origin',
                'trainCheckin.Destination',
                'trainCheckin.HafasTrip',
                'event')
                ->withCount('likes')
                ->whereHas('trainCheckin', function ($query) {
                    $query->where('departure', '<', date('Y-m-d H:i:s'))
                        ->where('arrival', '>', date('Y-m-d H:i:s'));
                })
                ->get()
                ->sortByDesc(function ($status) {
                    return $status->trainCheckin->departure;
                })->values();
        } else {
            $statuses = Status::with('user',
                'trainCheckin',
                'trainCheckin.Origin',
                'trainCheckin.Destination',
                'trainCheckin.HafasTrip',
                'event')
                ->whereHas('trainCheckin', function ($query) {
                    $query->where('departure', '<', date('Y-m-d H:i:s'))
                        ->where('arrival', '>', date('Y-m-d H:i:s'));
                })
                ->where('user_id', $userId)
                ->first();
            return $statuses;
            //This line is important since we're using this method for two different purposes and I forgot that.
        }
        if ($statuses === null) {
            return null;
        }
        $polylines = $statuses->map(function ($status) {
            return $status->trainCheckin->getMapLines();
        });
        if ($array == true) {
            return ['statuses' => $statuses->toArray(), 'polylines' => $polylines];
        }

        return ['statuses' => $statuses, 'polylines' => $polylines];
    }

    public static function getStatusesByEvent(int $eventId)
    {
        return Status::with('user',
            'trainCheckin',
            'trainCheckin.Origin',
            'trainCheckin.Destination',
            'trainCheckin.HafasTrip',
            'event')
            ->withCount('likes')
            ->where('event_id', '=', $eventId)
            ->orderBy('created_at', 'desc')
            ->latest()
            ->simplePaginate(15);
    }

    public static function getDashboard ($user) {
        $userIds = $user->follows()->pluck('follow_id');
        $userIds[] = $user->id;
        return Status::whereIn('user_id', $userIds)
            ->with('user',
                'trainCheckin',
                'trainCheckin.Origin',
                'trainCheckin.Destination',
                'trainCheckin.HafasTrip')
            ->withCount('likes')
            ->latest()->simplePaginate(15);
    }

    public static function getGlobalDashboard () {
        return Status::orderBy('created_at', 'desc')
            ->with('user',
                'trainCheckin',
                'trainCheckin.Origin',
                'trainCheckin.Destination',
                'trainCheckin.HafasTrip')
            ->withCount('likes')
            ->latest()->simplePaginate(15);
    }

    public static function DeleteStatus ($user, $statusId) {
        $status = Status::find($statusId);

        if ($status === null) {
            return null;
        }
        $trainCheckin = $status->trainCheckin()->first();

        if ($user != $status->user) {
            return false;
        }
        $user->train_distance -= $trainCheckin->distance;
        $user->train_duration -= (strtotime($trainCheckin->arrival) - strtotime($trainCheckin->departure)) / 60;

        //Don't subtract points, if status outside of current point calculation
        if (strtotime($trainCheckin->departure) >= date(strtotime('last thursday 3:14am'))) {
            $user->points -= $trainCheckin->points;
        }
        $user->update();
        $status->delete();
        $trainCheckin->delete();
        return true;
    }

    public static function EditStatus ($user, $statusId, $body, $businessCheck) {
        $status = Status::find($statusId);
        if ($status === null) {
            return null;
        }
        if ($user != $status->user) {
            return false;
        }
        $status->body = $body;
        $status->business = $businessCheck >= 1 ? 1 : 0;
        $status->update();
        return $status->body;
    }

    public static function CreateLike ($user, $statusId) {
        $status = Status::findOrFail($statusId);
        if (!$status) {
            return null;
        }
        $likesDataProvider = $user->likes()->where('status_id', $statusId)->first();
        if ($likesDataProvider) {
            return false;
        }

        $likesDataProvider = new LikesDataProvider();
        $likesDataProvider->user_id = $user->id;
        $likesDataProvider->status_id = $status->id;
        $likesDataProvider->save();
        $status->user->notify(new StatusLiked($likesDataProvider));
        return true;
    }


    /**
     *
     */

    public static function DestroyLike ($user, $statusId) {
        $likesDataProvider = $user->likes()->where('status_id', $statusId)->first();
        if ($likesDataProvider) {
            $likesDataProvider->delete();
            return true;
        }
        return false;
    }

    public static function getLikes ($statusId) {
        return Status::findOrFail($statusId)->likes()->with('user')->simplePaginate(15);
    }


    public static function usageByDay (Carbon $date) {
        return Status::where("created_at", ">=", $date->copy()->startOfDay())
            ->where("created_at", "<=", $date->copy()->endOfDay())
            ->count();
    }
}


