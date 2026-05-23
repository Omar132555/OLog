<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like;

class LikeFactory extends Factory
{
    public function definition(): array
    {
        do {

            $userId = User::inRandomOrder()->value('id');
            $postId = Post::inRandomOrder()->value('id');

        } while (
            Like::where('user_id', $userId)
                ->where('post_id', $postId)
                ->exists()
        );

        return [
            'user_id' => $userId,
            'post_id' => $postId,
        ];
    }
}