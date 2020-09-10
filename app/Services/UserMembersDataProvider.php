<?php

//     public function getDeliveryEmploye()
//     {
//         return $this->model->where(['role'=>'deliveryemploye'])->lists('name', 'id');
//     }

//     public function setOneSignalId(Request $request)
//     {
//         $userMember = auth()->user();
//         $userMember->onesignal_id = $request->input('player_id');
//         $userMember->save();
//         return $userMember;
//     }

//}


namespace App\Services;

use App\Models\Follow;

use App\Notifications\UserFollowed;
use App\Models\Profil;
use App\Models\Status;
use App\Models\TrainCheckin;
use App\Models\UserMember;
use Carbon\Carbon;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

// use Intervention\Image\ImageManagerStatic as Image;
use League\CommonMark\Inline\Element\Image;
use Tymon\JWTAuth\Token;

use App\Services\Contracts\IUserMembersDataProvider;
use App\Traits\DataProvider;

class UserMembersDataProvider extends IUserMembersDataProvider
{
    use DataProvider;

    /**
    *
    * @var Validator
    */
    private $validator;


    public function __construct(Validator $validator)
    {
        $this->validator = $validator;

       }


    public static function getProfilePicture($username)
    {
        $user = UserMember::where('username', $username)->first();
        if (empty($user)) {
            return null;
        }
        try {
            $ext     = pathinfo(public_path('/uploads/avatars/' . $user->avatar), PATHINFO_EXTENSION);
            $picture = File::get(public_path('/uploads/avatars/' . $user->avatar));
        } catch (\Exception $e) {
            $user->avatar = 'user.jpg';
        }

        if ($user->avatar === 'user.jpg') {
            $hash = 0;
            for ($i = 0; $i < strlen($username); $i++) {
                $hash = ord(substr($username, $i, 1)) + (($hash << 5) - $hash);
            }

            $hex = dechex($hash & 0x00FFFFFF);

            $picture = Image::canvas(512, 512, $hex)
                ->insert(public_path('/img/user.png'))
                ->encode('png')->getEncoded();
            $ext     = 'png';
        }

        return ['picture' => $picture, 'extension' => $ext];
    }

    public function deleteProfilePicture()
    {
        $user = Auth::user();
        if ($user->avatar != 'user.jpg') {
            File::delete(public_path('/uploads/avatars/' . $user->avatar));
            $user->avatar = 'user.jpg';
            $user->save();
        }

        return redirect(route('settings'));
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->currentpassword, $user->password) || empty($user->password)) {
            $this->validate($request, ['password' => ['required', 'string', 'min:8', 'confirmed']]);
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->back()->with('info', __('controller.user.password-changed-ok'));
        }
        return redirect()->back()->withErrors(__('controller.user.password-wrong'));
    }


    //delete sessions from user
    public function deleteSession() {
        $user = Auth::user();
        foreach ($user->sessions as $session) {
            $session->delete();
        }
        return redirect()->route('static.welcome');
    }

    //delete a specific session for user

    public function deleteToken($id) {
        $user = Auth::user();
        $token = Token::find($id);
        if ($token->user == $user) {
            $token->revoke();
        }
        return redirect()->route('settings');
    }

    public function destroyUser() {
        $user = Auth::user();

        if ($user->avatar != 'user.jpg') {
            File::delete(public_path('/uploads/avatars/' . $user->avatar));
        }
        foreach (Status::where('user_id', $user->id)->get() as $status) {
            $status->trainCheckin->delete();
            $status->likes()->delete();
            $status->delete();
        }

        $user->Profil()->delete();
        $user->follows()->delete();
        $user->followers()->delete();
        DatabaseNotification::where(['notifiable_id' => $user->id, 'notifiable_type' => get_class($user)])->delete();


        $user->delete();

        return redirect()->route('static.welcome');
    }

    //Save Changes on Settings-Page
    public function SaveAccount(Request $request) {

        $this->validator($request, [
            'firstname' => 'required|max:120'
        ]);
        $user       = UserMember::where('id', Auth::user()->id)->first();
        $user->name = $request['firstname'];
        $user->update();
        return redirect()->route('account');
    }

    // public static function getProfilPage($username) {
    //     $user = UserMember::where('username', 'like', $username)->first();
    //     if ($user === null) {
    //         return null;
    //     }
    //     $statuses = $user->statuses()->with('user',
    //     'trainCheckin',
    //     'trainCheckin.Origin',
    //     'trainCheckin.Destination',
    //     'trainCheckin.HafasTrip',
    //     'event')->orderBy('created_at', 'DESC')->paginate(15);

    //     $twitterUrl  = "";


    //     if ($user->Profil != null) {
    //         if (!empty($user->Profil->twitter_token) && !empty($user->Profil->twitter_tokenSecret)) {
    //             try {
    //                 $connection = new TwitterOAuth(
    //                     config('trwl.twitter_id'),
    //                     config('trwl.twitter_secret'),
    //                     $user->Profil->twitter_token,
    //                     $user->Profil->twitter_tokenSecret
    //                 );

    //                 $getInfo    = $connection->get('users/show', ['user_id' => $user->Profil->twitter_id]);
    //                 $twitterUrl = "https://twitter.com/" . $getInfo->screen_name;
    //             } catch (Exception $e) {
    //                 // The big whale time or $user has removed the api rights but has not told us yet.
    //             }
    //         }
    //     }


    //     $user->unsetRelation('Profil');

    //     return [
    //         'username' => $username,
    //         'twitterUrl' => $twitterUrl,
    //         'statuses' => $statuses,
    //         'user' => $user
    //     ];
    // }


    /**
     * @param UserMember The user who wants to see stuff in their timeline
     * @param int The user id of the person who is followed
     */
    public static function CreateFollow($user, $followId)
    {
        $follow = $user->follows()->where('follow_id', $followId)->first();
        if ($follow) {
            return false;
        }
        $follow            = new Follow();
        $follow->user_id   = $user->id;
        $follow->follow_id = $followId;
        $follow->save();

        UserMember::find($followId)->notify(new UserFollowed($follow));
        return true;
    }

    /**
     * @param UserMember The user who doesn't want to see stuff in their timeline anymore
     * @param int The user id of the person who was followed and now isn't
     */
    public static function DestroyFollow($user, $followId)
    {
        $follow = $user->follows()->where('follow_id', $followId)->where('user_id', $user->id)->first();
        if ($follow) {
            $follow->delete();
            return true;
        }
    }

    public static function getLeaderboard()
    {
        $user    = Auth::user();
        $friends = null;

        if ($user != null) {
            $userIds   = $user->follows()->pluck('follow_id');
            $userIds[] = $user->id;
            $friends   = UserMember::select('username',
                                      'train_duration',
                                      'train_distance',
                                      'points')
                ->where('points', '<>', 0)
                ->whereIn('id', $userIds)
                ->orderby('points', 'desc')
                ->limit(20)
                ->get();
        }
        $users      = UserMember::select('username',
                                   'train_duration',
                                   'train_distance',
                                   'points')
            ->where('points', '<>', 0)
            ->orderby('points', 'desc')
            ->limit(20)
            ->get();
        $kilometers = UserMember::select('username',
                                   'train_duration',
                                   'train_distance',
                                   'points')
            ->where('points', '<>', 0)
            ->orderby('train_distance', 'desc')
            ->limit(20)
            ->get();


        return ['users' => $users, 'friends' => $friends, 'kilometers' => $kilometers];
    }

    public static function registerByDay(Carbon $date)
    {
        $q = UserMember::where("created_at", ">=", $date->copy()->startOfDay())
            ->where("created_at", "<=", $date->copy()->endOfDay())
            ->count();
        return $q;
    }

    public static function updateDisplayName($displayname)
    {
        $request   = new Request(['displayname' => $displayname]);
        $validator = Validator::make($request->all(), [
            'displayname' => 'required|max:120'
        ]);
        if($validator->fails()){
            abort(400);
        }
        $user       = UserMember::where('id', Auth::user()->id)->first();
        $user->name = $displayname;
        $user->save();
    }
}
