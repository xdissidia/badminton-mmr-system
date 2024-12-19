<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonPlayerRating extends Model
{

    protected $fillable = [
        'season_id',
        'player_id',
        'value',
        'trueskill_mean',
        'trueskill_deviation',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function history()
    {
        return $this->hasMany(RatingHistory::class);
    }
}
