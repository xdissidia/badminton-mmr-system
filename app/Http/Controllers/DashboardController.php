<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Season;
use App\Traits\MatchMaker;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function index()
    {

        $season = 'Season 1';
        if (request()->has('season')) {
            $season = request()->season;
        }

        $data = [
            'season' => Season::with([
                'events' => function ($q) {
                    $q->orderBy('id', 'desc');
                },
                'events.games' => function ($q) {
                    $q->with('players')->orderBy('id', 'desc');
                }
            ])->where('name', $season)->first(),
            'players' => Player::with([
                'rating' => function ($q) use ($season) {
                    $s = Season::whereName($season)->first();
                    $q->where('season_id', $s->id);
                }
            ])->get()->sortByDesc('rating.value'),
            'seasons' => Season::get(),
        ];

        return view('dashboard.index', compact('data'));
    }
}
