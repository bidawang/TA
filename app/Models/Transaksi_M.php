<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pembayaran_M;

class Transaksi_M extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable =[
        'id_set_rental',
        'id_rental',
        'jam_mulai',
        'jam_selesai',
        'jumlah_jam',
        'total',
        'jenis',
        'keterangan',
        'google_id',
    ];

    public function pembayaran()
{
    return $this->hasOne(Pembayaran_M::class, 'transaksi_id', 'id_transaksi');
}
public function setRental(){
    return $this->hasOne(SetRental_M::class, 'id', 'id_set_rental');
}

}
