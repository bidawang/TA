<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental_M extends Model
{
    use HasFactory;
    protected $table = 'rental'; // pastikan sesuai nama tabel

    protected $fillable = [
        'nama',
        'logo',
        'id_alamat',
        'nik',
        'google_id',
        'deskripsi',
        'rating',
        'latitude',
        'longitude',
    ];

    // relasi ke tabel alamat (misalnya)
    public function alamat()
    {
        return $this->belongsTo(Alamat_M::class, 'id_alamat');
    }

    // jika ingin relasi ke user berdasarkan google_id
    public function user()
    {
        return $this->belongsTo(User::class, 'google_id');
    }

    public function setRental()
    {
        return $this->hasOne(SetRental_M::class); // Setiap rental memiliki satu rental detail
    }

    public function ratings()
{
    return $this->hasMany(Rating_M::class, 'rental_id'); // foreign key = rental_id
}


    public function averageRating()
{
    return $this->ratings()->avg('rating');
}

public function fasilitas()
{
    return $this->hasMany(Fasilitas_M::class, 'id_rental');
}

public function galeri()
{
    return $this->hasMany(Galeri_M::class, 'id_rental');
}
    
}
