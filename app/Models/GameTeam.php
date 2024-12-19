<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameTeam extends Model
{
    public function players()
    {
        return $this->hasMany(TeamPlayer::class);
    }
}
