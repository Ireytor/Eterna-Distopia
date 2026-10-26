<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(), 
            ]);

            Auth::login($user);

            return redirect('/dashboard'); 

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Hubo un problema al iniciar sesión con Google.');
        }
    }
}