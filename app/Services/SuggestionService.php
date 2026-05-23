<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SuggestionService
{
    public function suggest($userId)
    {
        $user = Auth::user();
        if ($userId) {
            $myFollowings = [$userId, $user->id];
        } else {
            $myFollowings = $user->following()->pluck('users.id');
        }
        $suggestOfMutual = User::where('id', '!=', $user->id)->whereDoesntHave('followers', function ($q) use ($user) {
            $q->where('follower_id', $user->id);
        })->withCount(['followers as mutual_counts' => function ($q) use ($myFollowings) {
            $q->whereIn('follower_id', $myFollowings);
        }])->orderByDesc('mutual_counts')
            ->limit(10)->get();

        return $suggestOfMutual;
    }
}
