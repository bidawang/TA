<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TV_M extends Model
{
    use HasFactory;
    protected $table = 'tv'; // Sesuaikan dengan nama tabel Anda

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'merek',
        'ukuran',
        'id_rental',
        'google_id',
    ];

    public function setrental()
    {
        return $this->hasMany(SetRental_M::class);
    }
}
