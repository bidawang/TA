<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePS_M extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'game_platforms';

}
