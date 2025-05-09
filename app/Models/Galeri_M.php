<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri_M extends Model
{
    use HasFactory;
    protected $table = 'galeri_rental';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama_foto',
        'deskripsi',
        'carousel',
        'id_rental',
        'google_id',
    ];

    // Relasi dengan tabel rental
    public function rental()
    {
        return $this->belongsTo(Rental_M::class, 'id_rental');
    }
}
