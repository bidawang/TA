<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran_M extends Model
{
    use HasFactory;
    protected $table = 'metode_pembayaran'; // Sesuaikan dengan nama tabel Anda

    // Kolom-kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'id_transaksi',
        'payment_method',
    ];

    public function transaksi()
{
    return $this->belongsTo(Transaksi_M::class, 'id_transaksi');
}

}
