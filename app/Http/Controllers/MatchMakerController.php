<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\MatchMaker as MM;
use App\Traits\MatchMaker;
use Illuminate\Http\Request;

class MatchMakerController extends Controller
{

    use MatchMaker;

    public function index()
    {
        $ps = Player::get();
        $ps = $ps->random(6);
        $players = Player::with([
        ])->get();

        return view('match-maker.index', compact('players'));
    }

    public function createMatches()
    {

        $ps = Player::whereIn('id', request()->players)->get();

        $matches = $this->getMatches($ps, request()->match_id);

        return $matches;
    }

    public function getMatchMakerMatches()
    {
        $mm = MM::with('matches')->where('name', request()->match_id)->first();
        $return = [];

        $return['match_id'] = $mm->name;
        $return['matches'] = [];
        $matches = [];
        foreach ($mm->matches as $m) {
            $matches[] = [
                [
                    $m->player1->name,
                    $m->player2->name,
                ],
                [
                    $m->player3->name,
                    $m->player4->name,
                ]
            ];
        }

        $return['matches'] = $matches;

        return $return;
    }

    public function updateMatchMakerMatches()
    {
        $ps = Player::whereIn('id', request()->players)->get();
        $this->updateMatches($ps, request()->match_id, request()->match_reset);
        $mm = MM::with('matches')->where('name', request()->match_id)->first();
        $return = [];
        $return['match_id'] = $mm->name;
        $return['matches'] = [];
        $matches = [];
        foreach ($mm->matches as $m) {
            $matches[] = [
                [
                    $m->player1->name,
                    $m->player2->name,
                ],
                [
                    $m->player3->name,
                    $m->player4->name,
                ]
            ];
        }
        $return['matches'] = $matches;
        return $return;
    }
}
