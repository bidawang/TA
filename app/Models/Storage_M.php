<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage_M extends Model
{
    use HasFactory;
    protected $table ="game_ps";
    protected $fillable=[
        'id_game',
        'id_ps',
    ];

    // Storage_M.php
public function game()
{
    return $this->belongsTo(Game_M::class, 'id_game');
}

}
