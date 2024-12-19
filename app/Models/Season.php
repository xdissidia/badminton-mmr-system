<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    //

    public function events()
    {
        return $this->hasMany(SeasonEvent::class);
    }
}
