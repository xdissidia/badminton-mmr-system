<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait MatchMaker
{
    public function getMatches($players)
    {
        $matches = [];
        $pa = [];
        foreach ($players as $player) {
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
                    $gn++;
                    $p = $p->sortBy('g');
                }
            }
            $p = $p->sortBy('g');
            $tu = [];
        }
        return $matches;
    }
}
