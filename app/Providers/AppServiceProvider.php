<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        $categories = Category::take(5)->get();
        View::share('categories', $categories);
        Gate::define('edit-post', function (User $user, Post $post) {
            return $post->user->is($user);
        });
        Gate::define('delete-comment', function (User $user, Comment $comment) {
            return $comment->user_id === $user->id
                || $comment->post->user_id === $user->id;
        });
    }
}
