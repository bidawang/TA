<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            // Jika tidak ditemukan, coba cari berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update google_id dan avatar
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                ]);
            } else {
                // Buat user baru
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar'    => $googleUser->avatar,
                    'role'      => 'user', // default role
                ]);
            }
        }

        // Login user
        Auth::login($user);
$rental = \App\Models\Rental_M::where('google_id', $user->google_id)->first();
        // dd($rental);
        // Simpan ke session kalau ada
        if ($rental) {
            session(['id_rental' => $rental->id]);
        }
        // Validasi no_hp setelah login
        $cleanPhone = preg_replace('/\D/', '', $user->no_hp);
        if (empty($cleanPhone) || $user->no_hp == NULL || strlen($cleanPhone) < 10 || strlen($cleanPhone) > 14) {
            return redirect()->route('user.edit', $user->google_id)->with('warning', 'Lengkapi nomor HP Anda dulu ya!');
        }

        // Ambil data rental (jika ada)
        
        return redirect()->route('dashboard');

    }catch (\Exception $e) {
    Log::error('Google Login Error: ' . $e->getMessage());
    return redirect()->route('dashboard')->with('error', 'Gagal login. Coba lagi nanti.');
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
