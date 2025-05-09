<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game_M extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'games';

    public function platforms()
    {
        return $this->belongsToMany(Platforms_M::class, 'game_platforms', 'platform_id', 'game_id');
    }
}
