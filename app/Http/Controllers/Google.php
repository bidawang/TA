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
                    // Jika tidak ada, cari user berdasarkan email
                    $user = User::where('email', $googleUser->email)->first();
        
                    if ($user) {
                        // Update google_id dan avatar jika user sudah ada
                        $user->update([
                            'google_id' => $googleUser->id,
                            'avatar' => $googleUser->avatar,
                        ]);
                        dd($user);
                    } else {
                        // Jika user belum ada, buat user baru
                        $user = User::create([
                            'name' => $googleUser->name,
                            'email' => $googleUser->email,
                            'google_id' => $googleUser->id,
                            'avatar' => $googleUser->avatar,
                            'role' => 'user', // default role
                        ]);
                    }
                }
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
                Auth::login($user);
        
                // Ambil rental terkait user (asumsi rental punya kolom google_id atau bisa disesuaikan)
                $rental = \App\Models\Rental_M::where('google_id', $user->google_id)->first();
        
                // Simpan id_rental ke session (jika ada)
                session(['id_rental' => $rental?->id]);
        
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
