<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Google extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google setelah user login.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                } else {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'role' => 'user', // default
                    ]);
                }
            }

            Auth::login($user);
            return redirect()->route('rental.index');
        } catch (\Exception $e) {
            return redirect()->route('rental.index')->with('error', 'Gagal login: ' . $e->getMessage());
        }
    }

    /**
     * Logout user dari sistem.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('rental.index');
    }

}
