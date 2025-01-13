<?php

namespace App\Models;

use App\Traits\MatchMaker;
use Illuminate\Database\Eloquent\Model;

class MatchMakerMatch extends Model
{
    protected $guarded = [];

    public function matchMaker()
    {

        return $this->belongsTo(MatchMaker::class);
    }

    public function player1()
    {

        return $this->hasOne(Player::class, 'id', 'p1');
    }
    public function player2()
    {

        return $this->hasOne(Player::class, 'id', 'p2');
    }
    public function player3()
    {

        return $this->hasOne(Player::class, 'id', 'p3');
    }
    public function player4()
    {

        return $this->hasOne(Player::class, 'id', 'p4');
    }
}
