<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat_M extends Model
{
    use HasFactory;
    protected $table = 'alamat'; // pastikan sesuai nama tabel
    protected $primaryKey = 'id_alamat';
    protected $fillable = [
        'rt', 'rw', 'kecamatan', 'kode_pos', 'google_id', 'provinsi', 'kota', 'kelurahan', 'alamat_lengkap'
    ];

    // Relasi satu ke banyak (Alamat bisa digunakan untuk banyak rental)
    public function rentals()
    {
        return $this->hasOne(Rental_M::class, 'id');
    }
}
