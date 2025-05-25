<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PS_M extends Model
{
    use HasFactory;
    protected $table = 'ps'; // Sesuaikan dengan nama tabel Anda

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'model_ps',
        'storage',
        'seri',
        'id_rental',
        'google_id',
    ];

    public function setrental()
    {
        return $this->hasMany(SetRental_M::class);
    }

    
}
