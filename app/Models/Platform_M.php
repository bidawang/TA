<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform_M extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'platforms';


    public function games()
    {
        return $this->belongsToMany(Game_M::class, 'game_platforms', 'platform_id', 'game_id');
    }
}
