<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLogs_M extends Model
{
    use HasFactory;
    protected $table= "wallet_logs";
    protected $fillable =[
        'amount',
        'type',
        'note',
        'status',
        'method',
        'id_rental',
        'google_id',
    ];
    public function rental(){
        return $this->hasOne(Rental_M::class, 'id', 'id_rental');
    }
    public function penolakan(){
        return $this->hasOne(Penolakan_M::class, 'id_wallet_logs');
    }
}
