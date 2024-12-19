<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    public function player()
    {
        return $this->hasMany(Player::class);
    }
}
