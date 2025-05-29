<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class HashidServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Daftar model yang ingin kamu HashID-kan otomatis
        $models = [
            \App\Models\Alamat_M::class,
            \App\Models\User::class,
            \App\Models\Fasilitas_M::class,
            \App\Models\Galeri_M::class,
            \App\Models\Game_M::class,
            \App\Models\GamePS_M::class,
            \App\Models\MetodePembayaran_M::class,
            \App\Models\Penolakan_M::class,
            \App\Models\Platform_M::class,
            \App\Models\PS_M::class,
            \App\Models\Rating_M::class,
            \App\Models\Rental_M::class,
            \App\Models\SetRental_M::class,
            \App\Models\Storage_M::class,
            \App\Models\Transaksi_M::class,
            \App\Models\Tripay_M::class,
            \App\Models\TV_M::class,
            \App\Models\UserWallet_M::class,
            \App\Models\Wallet_M::class,
            \App\Models\WalletLogs_M::class,
            // tambahin terus di sini
        ];

        foreach ($models as $model) {
            $model::macro('getRouteKey', function () {
                return Hashids::encode($this->getKey());
            });

            $model::macro('resolveRouteBinding', function ($value, $field = null) {
                $decoded = Hashids::decode($value);
                if (empty($decoded)) {
                    abort(404);
                }

                return $this->where($this->getKeyName(), $decoded[0])->firstOrFail();
            });
        }
    }

    public function register(): void
    {
        //
    }
}
