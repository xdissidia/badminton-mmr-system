<?php

namespace Database\Seeders;

use App\Http\Controllers\GameController;
use App\Models\Player;
use App\Models\Season;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $season = new Season;
        $season->name = "Season 1";
        $season->save();
        // GULOD 1
        $this->createPlayer('Jay');
        $this->createPlayer('May');
        $this->createPlayer('Lea');

        // GULOD 2
        $this->createPlayer('Ryza');
        $this->createPlayer('Loren');

        // GULOD 3
        $this->createPlayer('Tatek');
        $this->createPlayer('Pam');
        $this->createPlayer('Nad');

        // SANTA MARIA
        $this->createPlayer('Carlo');
        $this->createPlayer('Xandro');
        $this->createPlayer('Enrico');
        $this->createPlayer('Tamayo');
        $this->createPlayer('Charlize');

        // PAGASA
        $this->createPlayer('Ison');
        $this->createPlayer('Erica');
        $this->createPlayer('Tino');
        $this->createPlayer('Lery');
        $this->createPlayer('Alvin');
        $this->createPlayer('Baby Jean');

        $this->seedEvent1($season);
        // $season->events()->create([
        //     'name' => 'Dec 15, 2024',
        //     'datetime' => now(),
        // ]);
    }

    private function createPlayer($name)
    {
        Player::create([
            'name' => $name,
        ]);
        // ->rating()->create([
        //     'season_id'=> 1,
        //     'value' => 0,
        //     'trueskill_mean' => 25,
        //     'trueskill_deviation' => 8.3333333333333333333333333333333,
        // ]);
    }

    private function seedEvent1($season)
    {

        $season->events()->create([
            'name' => 'Nov 23, 2024 (SMBC-SAT)',
            'datetime' => now(),
        ]);
        $this->createGame(['Ison', 'Lea', 'Ryza', 'Tatek']);
        $this->createGame(['Jay', 'Tatek', 'Carlo', 'Tino']);
        $this->createGame(['Ison', 'Tino', 'Jay', 'Tatek']);
        $this->createGame(['Tatek', 'Tino', 'Carlo', 'Ison']);
        $this->createGame(['May', 'Ryza', 'Lea', 'Loren']);

        $this->createGame(['Jay', 'Ryza', 'Carlo', 'Lea']);
        $this->createGame(['Tatek', 'Tino', 'Ison', 'Jay']);
        $this->createGame(['Ison', 'Tatek', 'Jay', 'Tino']);


        $season->events()->create([
            'name' => 'Dec 5, 2024 (HyperSmash-THU)',
            'datetime' => now(),
        ]);

        $this->createGame(['Tatek', 'Pam', 'Xandro', 'Tamayo']);
        $this->createGame(['Tatek', 'Pam', 'Jay', 'Carlo']);
        $this->createGame(['Xandro', 'Tamayo', 'Jay', 'Carlo']);
        $this->createGame(['Tatek', 'Pam', 'Xandro', 'Tamayo']);
        $this->createGame(['Jay', 'Carlo', 'Tatek', 'Pam']);


        $season->events()->create([
            'name' => 'Dec 8, 2024 (SMBC-SUN)',
            'datetime' => now(),
        ]);

        $this->createGame(['Ison', 'Enrico', 'Jay', 'Ryza']);
        $this->createGame(['Jay ', 'Ryza', 'Ison', 'Carlo']);
        $this->createGame(['Ison', 'Ryza', 'Jay', 'Carlo']);
        $this->createGame(['Ison', 'May', 'Enrico', 'Ryza']);
        $this->createGame(['Jay', 'May', 'Carlo', 'Enrico']);
        $this->createGame(['Jay', 'Carlo', 'Ryza', 'May']);


        $season->events()->create([
            'name' => 'Dec 14, 2024 (SMBC-SUN)',
            'datetime' => now(),
        ]);

        $this->createGame(['Ison', 'Erica', 'Carlo', 'Enrico']);
        $this->createGame(['Jay', 'Lery', 'Ryza', 'Tino']);
        $this->createGame(['Ison', 'Erica', 'Xandro', 'Charlize']);
        $this->createGame(['Enrico', 'Tino', 'Carlo', 'Lery']);
        $this->createGame(['Ison', 'Erica', 'Jay', 'Ryza']);
        $this->createGame(['Xandro', 'Charlize', 'Carlo', 'Tino']);
        $this->createGame(['Jay', 'Ison', 'Xandro', 'Tino']);
        $this->createGame(['Tatek', 'Lery', 'Carlo', 'Enrico']);
        $this->createGame(['Jay', 'Tino', 'Xandro', 'Lery']);
        $this->createGame(['Ryza', 'Erica', 'Pam', 'Charlize']);
        $this->createGame(['Ryza', 'Charlize', 'Pam', 'Erica']);
        $this->createGame(['Jay', 'Tatek', 'Ison', 'Tino']);
        $this->createGame(['Ison', 'Erica', 'Tatek', 'Pam']);
        $this->createGame(['Ison', 'Ryza', 'Tatek', 'Tino']);
    }

    private function createGame($players)
    {

        $gc = new GameController;
        request()->merge([
            'players' => $this->getPlayers($players),
        ]);
        $gc->store(request());
    }

    private function getPlayers($palyers)
    {
        $player_ids = [];
        foreach ($palyers as $name) {
            try {
                $player_ids[] = Player::whereName($name)->first()->id;
            } catch (\Throwable $th) {
                dd($name);
            }
        }
        return $player_ids;
    }
}
