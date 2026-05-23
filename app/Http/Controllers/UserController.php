<?php

namespace App\Http\Controllers;

use App\Http\Requests\editProfilePhotoRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\emailVerificationMail;
use App\Models\Post;
use App\Models\User;
use App\Services\SuggestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function index(User $user, Request $request)
    {
        if ($request->n) {
            $notification = $user->notifications()->where('id', $request->n)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        $suggest = new SuggestionService();
        $suggestions = $suggest->suggest($user->id);
        return view('User.profile', ['user' => $user, 'suggestions' => $suggestions]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $section = request()->query('section', 'profile');
        $profile = request()->query('profile', 'account');
        $followers = $user->followers()->get();
        $following = $user->following()->get();

        return view('User.Dashboard', ['user' => $user, 'section' => $section, 'profile' => $profile, 'followers' => $followers, 'following' => $following]);
    }

    public function edit(){

    }

    public function update(UpdateUserRequest $request)
    {
        Auth::user()->update($request->validated());
        return to_route('user.dashboard');
    }

    public function destroy()
    {
        Auth::user()->delete();
        return to_route('login');
    }
    public function editProfilePhoto(editProfilePhotoRequest $request, User $user)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->image ?? '');
            $path = $request->file('image')->store('posts', 'public');
        }
        if (isset($path)) {
            $user->update([
                'image' => $path,
            ]);
        }

        return to_route('user.dashboard');
    }
    public function editProfileCover(editProfilePhotoRequest $request, User $user)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->image2 ?? '');
            $path = $request->file('image')->store('posts', 'public');
        }
        if (isset($path)) {
            $user->update([
                'image2' => $path,
            ]);
        }

        return to_route('user.dashboard');
    }

    public function feed()
    {
        $noFollows = false;
        $ids = Auth::user()->following()->pluck('users.id');
        if (! $ids->isEmpty()) {
            $posts = Post::whereIn('user_id', $ids)
                ->with(['user', 'comments', 'likedByUsers' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                }])
                ->paginate(10);
        } else {
            $noFollows = true;
            $posts = false;
        }

        return view('User.feed', ['posts' => $posts, 'noFollows' => $noFollows]);
    }
    
    public function emailShow()
    {
        $user = Auth::user();
        $cacheKey = 'email_verify'. md5($user->email);
        $count = Cache::get($cacheKey, 0);
        if($count >= 3){
            return back()->with('status2', 'You excceded the limit of request today, please try after a day'); 
        }
        Mail::to($user->email)->send(new emailVerificationMail($user, url(route('email.verfiy', $user->email))));
        Cache::put($cacheKey, $count + 1, 86400);
        return back()->with('status1', 'Email Sent Successfully');
    }

    public function emailVerify($email)
    {
        $user = User::where('email', $email)->first();
        $user->update([
            'email_verified_at' => now()
        ]);
        return to_route('user.dashboard');
    }
}
