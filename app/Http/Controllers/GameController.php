<?php

namespace App\Http\Controllers;

use App\Models\EventGame;
use App\Models\Player;
use App\Models\Season;
use App\Models\SeasonEvent;
use App\Models\SeasonPlayerRating;
use Illuminate\Http\Request;
use Laragod\Skills\GameInfo;
use Laragod\Skills\Player as TSPlayer;
use Laragod\Skills\Rating;
use Laragod\Skills\Team;
use Laragod\Skills\Teams;
use Laragod\Skills\TrueSkill\TwoTeamTrueSkillCalculator;

class GameController extends Controller
{
    public function create()
    {
        $data = [
            'players' => Player::all(),
        ];
        return view('game.create', compact('data'));
    }

    public function store(Request $request)
    {
        $season = 1;
        $event = SeasonEvent::get()->last();

        $game = new EventGame;
        $game->season()->associate($season);
        $game->event()->associate($event);
        $game->save();

        $p1 = Player::with('rating')->whereId($request->players[0])->first();
        $p2 = Player::with('rating')->whereId($request->players[1])->first();
        $p3 = Player::with('rating')->whereId($request->players[2])->first();
        $p4 = Player::with('rating')->whereId($request->players[3])->first();

        $gp1 = $this->addPlayer($season, $game, $p1, 1, 1);
        $gp2 = $this->addPlayer($season, $game, $p2, 2, 1);
        $gp3 = $this->addPlayer($season, $game, $p3, 3, 2);
        $gp4 = $this->addPlayer($season, $game, $p4, 4, 2);

        $ratings = $this->calculateRating($p1, $p2, $p3, $p4);
        $r1 = $this->getPlayerRating($ratings, 0);
        $r2 = $this->getPlayerRating($ratings, 1);
        $r3 = $this->getPlayerRating($ratings, 2);
        $r4 = $this->getPlayerRating($ratings, 3);
        $gp1->update([
            'rating_updated' => $r1['value'],
            'rating_deviation' => $r1['value'] - $gp1->rating_current,
        ]);
        $gp2->update([
            'rating_updated' => $r2['value'],
            'rating_deviation' => $r2['value'] - $gp2->rating_current,
        ]);
        $gp3->update([
            'rating_updated' => $r3['value'],
            'rating_deviation' => $r3['value'] - $gp3->rating_current,
        ]);
        $gp4->update([
            'rating_updated' => $r4['value'],
            'rating_deviation' => $r4['value'] - $gp4->rating_current,
        ]);
        SeasonPlayerRating::updateOrCreate([
            'season_id' => 1,
            'player_id' => $p1->id,
        ], $r1)->history()->create($r1);
        SeasonPlayerRating::updateOrCreate([
            'season_id' => 1, 'player_id' => $p2->id,
        ], $r2)->history()->create($r2);

        SeasonPlayerRating::updateOrCreate([
            'season_id' => 1, 'player_id' => $p3->id,
        ], $r3)->history()->create($r3);

        SeasonPlayerRating::updateOrCreate([
            'season_id' => 1, 'player_id' => $p4->id,
        ], $r4)->history()->create($r4);

    }

    private function getPlayerRating($ratings, $index)
    {
        return [
            'value' => $ratings['values'][$index],
            'trueskill_mean' => $ratings['ratings'][$index]->getMean(),
            'trueskill_deviation' => $ratings['ratings'][$index]->getStandardDeviation(),
        ];
    }

    private function getCurrentRating($player)
    {

        if ($player->rating) {
            return new Rating(
                $player->rating->trueskill_mean,
                $player->rating->trueskill_deviation,
            );
        }
        $gameInfo = new GameInfo();
        return $gameInfo->getDefaultRating();
    }

    private function computeCurrentValue($rating, $player)
    {
        $newRating = $rating->getRating($player);
        return (int) (($newRating->getMean() - (3 * $newRating->getStandardDeviation())) * 100);
    }

    private function calculateRating($p1, $p2, $p3, $p4)
    {
        $calculator = new TwoTeamTrueSkillCalculator();
        $player1 = new TSPlayer(1);
        $player2 = new TSPlayer(2);
        $gameInfo = new GameInfo();
        $team1 = new Team();
        $team1->addPlayer($player1, $this->getCurrentRating($p1));
        $team1->addPlayer($player2, $this->getCurrentRating($p2));
        $player3 = new TSPlayer(3);
        $player4 = new TSPlayer(4);
        $team2 = new Team();
        $team2->addPlayer($player3, $this->getCurrentRating($p3));
        $team2->addPlayer($player4, $this->getCurrentRating($p4));
        $teams = Teams::concat($team1, $team2);
        $newRatingsWinLose = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2]);
        $ratings[] = $newRatingsWinLose->getRating($player1);
        $ratings[] = $newRatingsWinLose->getRating($player2);
        $ratings[] = $newRatingsWinLose->getRating($player3);
        $ratings[] = $newRatingsWinLose->getRating($player4);
        $values[] = $this->computeCurrentValue($newRatingsWinLose, $player1);
        $values[] = $this->computeCurrentValue($newRatingsWinLose, $player2);
        $values[] = $this->computeCurrentValue($newRatingsWinLose, $player3);
        $values[] = $this->computeCurrentValue($newRatingsWinLose, $player4);

        return [
            'ratings' => $ratings,
            'values' => $values,
        ];

    }

    private function addPlayer($season, $game, $player, $player_number, $team_number)
    {
        return $game->players()->create([
            'player_number' => $player_number,
            'team' => 'Team ' . $team_number,
            'result' => in_array($player_number, [1, 2]) ? 'Win' : 'Lose',
            'season_id' => $season,
            'player_id' => $player->id,
            'rating_current' => @$player->rating->value ?? 0,
            'rating_deviation' => @$player->rating->value,
            'rating_updated' => @$player->rating->value,
        ]);
    }

}
