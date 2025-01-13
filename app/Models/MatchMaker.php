<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchMaker extends Model
{

    protected $guarded = [];

    public function matches()
    {

        return $this->hasMany(MatchMakerMatch::class);
    }
}
