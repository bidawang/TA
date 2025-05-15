<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tripay_M extends Model
{
    use HasFactory;
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
        return $this->belongsTo(Transaksi::class);
    }

    // Tentukan format waktu untuk `paid_at` jika diperlukan
    protected $dates = ['paid_at'];
}
