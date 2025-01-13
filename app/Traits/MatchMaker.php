<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use App\Models\MatchMaker as MMaker;

trait MatchMaker
{
    public function getMatches($players, $match_id)
    {
        $mm = MMaker::create([
            'name' => $match_id,
        ]);
        $matches = [];
        $pa = [];
        foreach ($players->pluck('name') as $player) {
            $pa[] = [
                'n' => $player,
                'g' => 0,
            ];
        }
        $p = collect($pa);
        $tu = [];
        $p = $p->shuffle();
        $rs = false;
        $gn = 1;
        for ($y = 1; $y <= 2; $y++) {
            for ($i = 1; $i <= 50; $i++) {
                $c = 0;
                $vs = [];
                $gc = [];
                foreach ($p as $k => $v) {
                    if ($c++ > 3)
                        break;
                    if (!$rs)
                        $v['g']++;
                    $p[$k] = $v;
                    $vs[] = $v['n'];
                    $gc[] = $v['g'];
                }
                for ($x = 0; $x <= 9999; $x++) {
                    shuffle($vs);
                    $t1 = [$vs[0], $vs[1]];
                    $t2 = [$vs[2], $vs[3]];
                    sort($t1);
                    sort($t2);
                    if (!in_array(join($t1), $tu) && !in_array(join($t2), $tu)) {
                        $tu[] = join($t1);
                        $tu[] = join($t2);
                        $rs = false;
                        break;
                    } else {
                        $rs = true;
                    }
                }
                if ($rs) {
                    $p = $p->shuffle();
                } else {
                    Log::info("GAME {$gn} : " . join(' ', $t1) . ' vs ' . join(' ', $t2));
                    $matches[] = [
                        $t1,
                        $t2,
                    ];
                    $p = $p->sortBy('g');
                    $mm->matches()->create([
                        'iteration' => $y,
                        'number' => $gn,
                        'p1' => $players->firstWhere('name', $t1[0])->id,
                        'p1_count' => $p->firstWhere('n', $t1[0])['g'],
                        'p2' => $players->firstWhere('name', $t1[1])->id,
                        'p2_count' => $p->firstWhere('n', $t1[1])['g'],
                        'p3' => $players->firstWhere('name', $t2[0])->id,
                        'p3_count' => $p->firstWhere('n', $t2[0])['g'],
                        'p4' => $players->firstWhere('name', $t2[1])->id,
                        'p4_count' => $p->firstWhere('n', $t2[1])['g'],
                    ]);
                    $gn++;
                }
            }
            $p = $p->sortBy('g');
            $tu = [];
        }
        return [
            'match_id' => $match_id,
            'matches' => $matches
        ];
    }


    public function updateMatches($players, $match_id, $match_reset = false)
    {
        $mm = MMaker::whereName($match_id)->with('matches')->first();
        $lm = $mm->matches()->where('number', $match_reset - 1)->first();
        $mm->matches()->where('number', '>=', $match_reset)->delete();
        $matches = [];
        $pa = [];
        $lmc = [];
        if ($match_reset > 1) {
            $lmc[] = [
                'n' => $lm->p1,
                'g' => $lm->p1_count,
            ];

            $lmc[] = [
                'n' => $lm->p2,
                'g' => $lm->p2_count,
            ];

            $lmc[] = [
                'n' => $lm->p3,
                'g' => $lm->p3_count,
            ];

            $lmc[] = [
                'n' => $lm->p4,
                'g' => $lm->p4_count,
            ];
        }
        $lmc = collect($lmc);
        $mgc = $lmc->min('g') + 1;
        foreach ($players as $player) {
            $gc = $mgc;
            $l = $lmc->firstWhere('n', $player->id);
            if ($l) {
                $gc = $l['g'];
            }
            $pa[] = [
                'n' => $player->name,
                'g' => $gc,
            ];
        }
        $p = collect($pa);
        $tu = [];
        $p = $p->shuffle();
        $rs = false;
        $gn = $match_reset;
        for ($y = $lm->iteration ?? 1; $y <= 2; $y++) {
            for ($i = 1; $i <= 50; $i++) {
                $c = 0;
                $vs = [];
                $gc = [];
                foreach ($p as $k => $v) {
                    if ($c++ > 3)
                        break;
                    if (!$rs)
                        $v['g']++;
                    $p[$k] = $v;
                    $vs[] = $v['n'];
                    $gc[] = $v['g'];
                }
                for ($x = 0; $x <= 9999; $x++) {
                    shuffle($vs);
                    $t1 = [$vs[0], $vs[1]];
                    $t2 = [$vs[2], $vs[3]];
                    sort($t1);
                    sort($t2);
                    if (!in_array(join($t1), $tu) && !in_array(join($t2), $tu)) {
                        $tu[] = join($t1);
                        $tu[] = join($t2);
                        $rs = false;
                        break;
                    } else {
                        $rs = true;
                    }
                }
                if ($rs) {
                    $p = $p->shuffle();
                } else {
                    Log::info("GAME {$gn} : " . join(' ', $t1) . ' vs ' . join(' ', $t2));
                    $matches[] = [
                        $t1,
                        $t2,
                    ];
                    $p = $p->sortBy('g');
                    $mm->matches()->create([
                        'iteration' => $y,
                        'number' => $gn,
                        'p1' => $players->firstWhere('name', $t1[0])->id,
                        'p1_count' => $p->firstWhere('n', $t1[0])['g'],
                        'p2' => $players->firstWhere('name', $t1[1])->id,
                        'p2_count' => $p->firstWhere('n', $t1[1])['g'],
                        'p3' => $players->firstWhere('name', $t2[0])->id,
                        'p3_count' => $p->firstWhere('n', $t2[0])['g'],
                        'p4' => $players->firstWhere('name', $t2[1])->id,
                        'p4_count' => $p->firstWhere('n', $t2[1])['g'],
                    ]);
                    $gn++;
                }
            }
            $p = $p->sortBy('g');
            $tu = [];
        }
        return [
            'match_id' => $match_id,
            'matches' => $matches
        ];
    }
}
