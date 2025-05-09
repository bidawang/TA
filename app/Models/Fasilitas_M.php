<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas_M extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'nama_fasilitas',
        'id_rental',
        'google_id',
        'foto_fasilitas',
    ];

    // Relasi ke Rental (jika ada model Rental_M)
    public function rental()
    {
        return $this->belongsTo(Rental_M::class, 'id_rental');
    }

    // Relasi ke User (jika google_id disimpan di model User)
    public function user()
    {
        return $this->belongsTo(User_M::class, 'google_id', 'google_id');
    }
}
