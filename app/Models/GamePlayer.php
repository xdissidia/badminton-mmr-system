<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{

    protected $fillable = [
        'season_id',
        'player_id',
        'player_number',
        'team',
        'result',
        'rating_current',
        'rating_deviation',
        'rating_updated',
    ];

    public function player()
    {
        $this->hasOne(Player::class);
    }

    public function game()
    {
        $this->belongsTo(EventGame::class);
    }

    public function season()
    {
        $this->belongsTo(Season::class);
    }
}
