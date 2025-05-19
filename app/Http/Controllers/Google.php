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

        // Cari user berdasarkan google_id
        $user = User::where('google_id', $googleUser->id)->first();

        if (!$user) {
            // Jika tidak ada user berdasarkan google_id, cari berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Jika email cocok, update google_id dan avatar
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                // Jika user belum ada sama sekali, buat user baru
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'role'      => 'user', // default role
                ]);
            }
        }

        // Login user ke sistem
        Auth::login($user);

        // Ambil data rental terkait user (jika ada)
        $rental = \App\Models\Rental_M::where('google_id', $user->google_id)->first();

        // Simpan id rental ke session
        session(['id_rental' => $rental?->id]);

        return redirect()->route('dashboard');

    } catch (\Exception $e) {
        return redirect()->route('dashboard')->with('error', 'Gagal login: ' . $e->getMessage());
    }
}


    /**
     * Logout user dari sistem.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('dashboard');
    }

}
