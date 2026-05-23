<?php

namespace App\Http\Controllers;

use App\Events\NotificationCounterEvent;
use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->id == $request->id) {
                return;
            }
            $cacheKey = 'follow'.md5($request->id);
            $follow_button_clicked = Cache::get($cacheKey, false);
            $is_now_following = $user->isFollowing($request->id);
            $followed = User::find($request->id);
            $user->following()->toggle($request->id);
            if (! $is_now_following) {
                if (! $follow_button_clicked) {
                    $followed->notify(new NewFollowerNotification(null, $user->name, null, $user->image, now(), $user->id, $followed->id));
                    event(new NotificationCounterEvent(
                        $followed->unreadNotifications()->count(),
                        $followed->id
                    ));
                    Cache::put($cacheKey, true, 60 * 2);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Pusher failed: '.$e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            '$follow_button_clicked' => $follow_button_clicked,
            '$is_now_following' => $is_now_following,
        ]);
    }

    public function suggest($userId)
    {
        $user = Auth::user();
        if($userId)
        {
            $myFollowings = [$userId, $user->id];
        }
        else{
            $myFollowings = $user->following()->pluck('users.id');
        }
        $suggestOfMutual = User::where('id', '!=', $user->id)->whereDoesntHave('followers', function ($q) use ($user) {
            $q->where('follower_id', $user->id);
        })->withCount(['followers as mutual_counts' => function ($q) use ($myFollowings) {
            $q->whereIn('follower_id', $myFollowings);
        }])->orderByDesc('mutual_counts')
            ->limit(5)->get();

        return response()->json(
            [
                'myFollowings' => $myFollowings,
                'suggestOfMutual' => $suggestOfMutual,
            ]
        );
    }
}
// Instead of saying people I follow, people which have followers that I'am one of them
// Get users that are mutual with users which I follow and which I'm not following and not me
// if he was following him then the unfollowd should not be notified
// if this button clicked within 2 mins then the user shouldn't be notified and if he was following the user  AND the cache should'nt updated
// the cache shouldn't updated if he was following the user

// my followings
// step 1 => Get all users except me
// step 2 => Get from them users which I'm not following
// step 3 => Get followers of these users which they are followers of the same people I follow

// step 2
// هات كل ال users الي انا مش follower عندهم

// انا عايز اجيب الي بيتابع الي انا بتابعه
// يبقى انا اقدر اجيب كل الناس الي هما follower_id عنده ما عادا انا
