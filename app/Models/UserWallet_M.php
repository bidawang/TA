<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet_M extends Model
{
    use HasFactory;
    protected $table= "user_wallets";
    protected $fillable =[
        'balance',
        'id_rental',
        'google_id',
    ];
}
