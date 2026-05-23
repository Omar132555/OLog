<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class RegisterController extends Controller
{
    public function create()
    {
        return view('Auth.Register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        Auth::login($user);

        return response()->json([
            'status' => true,
            'redirect' => route('posts.index'),
        ]);
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user_exist = User::where('email', $googleUser->email)->first();
        if ($user_exist) {
            $password = $user_exist->password;
            }
            else{
                $password = bcrypt('google'.$googleUser->email);
            }
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => $password,
                    'email_verified_at' => now(),
                ]
            );


        Auth::login($user);

        return to_route('Home');
    }
}
