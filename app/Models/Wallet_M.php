<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet_M extends Model
{
    use HasFactory;

    protected $table= "wallet";
    protected $primaryKey = "id_wallet";
    protected $fillable =[
        'provider',
        'kode_provider',
        'id_rental',
        'google_id',
    ];
}
