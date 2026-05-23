<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(PostController::class)->group(function () {
    Route::get('/', 'home')->name('Home');
    Route::get('/posts', 'index')->name('posts.index');
    Route::get('/posts/create', 'create')->name('posts.create');
    Route::get('/postSearch', 'search')->name('posts.search');
    Route::get('/category', 'category')->name('posts.category');
    Route::middleware('auth')->group(function () {
        Route::post('/posts/store', 'store')->name('posts.store');
        Route::post('/post/save/{post}', 'savePost')->name('posts.save');
        Route::post('/like/{post}/{user}', 'postLike')->name('posts.like');
        Route::can('edit-post', 'post')->group(function () {
            Route::put('/posts/{post}', 'update')->name('posts.update');
            Route::delete('posts/{post}', 'destroy')->name('posts.destroy');
            Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
        });
    });
    Route::get('/posts/{post}', 'show')->name('posts.show');
});

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'store')->name('login.store');
    Route::get('/login', 'create')->name('login');
    Route::delete('/logout', 'destroy')->name('logout');
    Route::get('/forgot-password', 'forgetLink')->middleware('guest')->name('password.forget');
    Route::post('/forgot-password', 'forget')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}/{email}', 'reset')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'updatePass')->middleware('guest')->name('password.update');
});

Route::get('/test', function () {
    return view('mail.email-verify');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'create')->name('register');
    Route::post('/register', 'store')->name('register.store');
    Route::get('/auth/redirect', 'redirect')->name('auth.redirect');
    Route::get('/auth/callback', 'callback')->name('auth.callback');
});

Route::controller(UserController::class)->group(function () {
    Route::middleware('auth')->prefix('/user')->group(function () {
        Route::get('dashboard', 'dashboard')->name('user.dashboard');
        Route::get('feed', 'feed')->name('user.feed');
        Route::get('email/show', 'emailShow')->name('email.show');
        Route::get('email/verify/{email}', 'emailVerify')->name('email.verfiy');
        Route::put('/{user}', 'update')->name('user.update');
        Route::put('photo/{user}', 'editProfilePhoto')->name('user.edit.photo');
        Route::put('cover/{user}', 'editProfileCover')->name('user.edit.cover');
        Route::get('/profile/{user}', 'index')->name('user.profile');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('user.delete');
        Route::put('/{user}/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    });
});
Route::middleware('auth')->group(function () {
    Route::post('/notification/markAllAsRead', [notificationController::class, 'markAllAsRead'])->name('notification.allRead');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/search', [CategoryController::class, 'search'])->name('category.search');
    Route::get('/follow', [FollowController::class, 'follow'])->name('follow');
    Route::post('/comment/store', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/chat/messages/{user}', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/messages/{user}', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/messages/{userId}/read', [ChatController::class, 'markAsRead']);
    Route::get('/suggest/{id}', [FollowController::class, 'suggest'])->name('suggest');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.delete');
    Route::post('/notification/{notification}', [notificationController::class, 'markAsRead'])->name('notification.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});
