<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi_M;
use Illuminate\Support\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
            Carbon::setLocale('id');

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // Ambil transaksi "penting" buat notifikasi (misal pending atau disetujui)
                $transaksiNotif = Transaksi_M::with('rental','setRental')
                    ->when($user->role === 'user', function ($q) use ($user) {
                        $q->where('google_id', $user->google_id)
                          ->whereIn('status', ['disetujui']);
                    })
                    ->when(in_array($user->role, ['admin']), function ($q) use ($user) {
                        $q->whereIn('status', ['pending'])
                          ->when($user->role === 'admin', function ($q) use ($user) {
                              $q->whereHas('rental', fn($qr) => $qr->where('google_id', $user->google_id));
                          });
                    })
                    ->latest()
                    ->take(5)
                    ->get();
                $view->with('transaksiNotif', $transaksiNotif);
            }
        });
    }
}
