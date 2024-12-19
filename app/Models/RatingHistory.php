<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingHistory extends Model
{
    protected $fillable = [
        'season_id',
        'player_id',
        'value',
        'trueskill_mean',
        'trueskill_deviation',
    ];

}
