<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGame extends Model
{
    protected $fillables = [
        'name',
        'caption',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function event()
    {
        return $this->belongsTo(SeasonEvent::class, 'season_event_id');
    }

    public function players()
    {
        return $this->hasMany(GamePlayer::class);
    }
}
