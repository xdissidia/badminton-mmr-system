<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Season;

class DashboardController extends Controller
{

    public function index()
    {

        $data = [
            'season' => Season::with(['events' => function ($q) {
                $q->orderBy('id', 'desc');
            }, 'events.games' => function ($q) {
                $q->with('players')->orderBy('id', 'desc');
            }])->first(),
            'players' => Player::with('ratings')->get()->sortByDesc('rating.value'),
        ];

        return view('dashboard.index', compact('data'));
    }
}
