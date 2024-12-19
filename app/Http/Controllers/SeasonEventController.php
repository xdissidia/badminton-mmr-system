<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\SeasonEvent;
use Illuminate\Http\Request;

class SeasonEventController extends Controller
{
    public function eventCreate(Request $request)
    {
        $season = Season::first();
        $event = new SeasonEvent;
        $event->season()->associate($season);
        $event->name = $request->event_name;
        $event->datetime = now();
        $event->save();

        return response([
            'message' => 'Created.',
        ]);
    }
}
