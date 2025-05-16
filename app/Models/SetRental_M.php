<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetRental_M extends Model
{
    use HasFactory;
    protected $table = 'set_rental'; // Sesuaikan dengan nama tabel Anda

    // Kolom-kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'rental_id',
        'tv_id',
        'ps_id',
        'name',
        'harga_per_jam',
    ];

    public function tv()
    {
        return $this->belongsTo(TV_M::class, 'tv_id'); // Rental detail memiliki satu TV
    }

    // Relasi dengan model PS
    public function ps()
    {
        return $this->belongsTo(PS_M::class, 'ps_id'); // Rental detail memiliki satu PS
    }

    public function rental()
    {
        return $this->belongsTo(Rental_M::class); // Setiap rental detail dimiliki oleh satu rental
    }
    public function transaksi()
    {
        return $this->belongsTo(Transaksi_M::class); // Setiap rental detail dimiliki oleh satu rental
    }
}
