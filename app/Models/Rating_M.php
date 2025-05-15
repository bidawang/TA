<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating_M extends Model
{
    use HasFactory;
    protected $table = "rating";
    protected $fillable = [
        'rental_id',
        'user_id',
        'rating',
        'komentar'
    ];

    public function rental() {
        return $this->belongsTo(Rental_M::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'google_id');
    }
}
