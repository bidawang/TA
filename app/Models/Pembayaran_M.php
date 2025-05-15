<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran_M extends Model
{
    use HasFactory;

    // Tentukan nama tabel, karena nama default adalah plural
    protected $table = 'pembayaran';

    // Tentukan kolom yang bisa diisi (fillable)
    protected $fillable = [
        'transaksi_id',
        'merchant_ref',
        'reference',
        'amount',
        'payment_method',
        'status',
        'checkout_url',
        'paid_at'
    ];

    // Tentukan relasi dengan model Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi_M::class);
    }

    // Tentukan format waktu untuk `paid_at` jika diperlukan
    protected $dates = ['paid_at'];
}
