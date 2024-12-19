<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    protected $fillable = [
        'name',
    ];

    public function rating()
    {
        return $this->hasOne(SeasonPlayerRating::class)->latestOfMany();
    }

    public function ratings()
    {
        return $this->hasMany(SeasonPlayerRating::class);
    }
}
