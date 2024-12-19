<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonEvent extends Model
{

    protected $fillables = [
        'name',
        'caption',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function games()
    {
        return $this->hasMany(EventGame::class);
    }
}
