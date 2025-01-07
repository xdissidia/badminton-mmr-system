<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SMBG - TrueSkill (Alpha) </title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>

<style>
    body {
        font-family: "monospace";
    }

    div {
        font-family: "monospace";
    }

    .superscript {
        position: relative;
        top: -2px;
        font-size: 80%;
    }
</style>
@php

    function autoPad($str, $n = 1)
    {
        $s = strlen($str);
        if ($s > $n) {
            $n = $s + 1;
        }
        $r = $str;
        for ($i = $s; $i < $n; $i++) {
            $r .= '&nbsp;';
        }
        return $r;
    }
    function newRating($player, $gp, $win = true)
    {
        $win_color = $win ? 'style="color:green"' : 'style="color:red"';
        return $player->name . '<span class="superscript" ' . $win_color . '>' . ($gp->rating_deviation > 0 ? '+' . $gp->rating_deviation : $gp->rating_deviation) . '</span>';
    }

    $season = 'Season 1';
    if (request()->has('season')) {
        $season = request()->season;
    }
@endphp

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div style="width: 100%; display: table;">
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">
                <code><a href="#" class='create-event'>{{ @$data['season']->name }}</a></code>
                <br>
                @if(isset($data['season']))
                            @foreach ($data['season']->events as $event)
                                        <code><a href="{{ route('game.store', 'season=' . $season) }}" class='create-game'>{{ $event->name }}</a></code>
                                        @php
                                            $i = $event->games->count();
                                        @endphp
                                        @foreach ($event->games as $game)
                                                @php
                                                    $gp1 = $game->players->firstWhere('player_number', 1);
                                                    $gp2 = $game->players->firstWhere('player_number', 2);
                                                    $gp3 = $game->players->firstWhere('player_number', 3);
                                                    $gp4 = $game->players->firstWhere('player_number', 4);
                                                    $player1 = $data['players']->firstWhere('id', $gp1->player_id);
                                                    $player2 = $data['players']->firstWhere('id', $gp2->player_id);
                                                    $player3 = $data['players']->firstWhere('id', $gp3->player_id);
                                                    $player4 = $data['players']->firstWhere('id', $gp4->player_id);

                                                    $mp1 = $player1->name;
                                                    $mp2 = $player2->name;
                                                    $mp3 = $player3->name;
                                                    $mp4 = $player4->name;

                                                    $mp1 = newRating($player1, $gp1, 1);
                                                    $mp2 = newRating($player2, $gp2, 1);
                                                    $mp3 = newRating($player3, $gp3, 0);
                                                    $mp4 = newRating($player4, $gp4, 0);

                                                    // $mp1 = $player1->name . '<span class="superscript"> ' . ($gp1->rating_deviation > 0 ? '' . $gp1->rating_deviation : $gp1->rating_deviation) . '</span>';
                                                    // $mp2 = $player2->name . ' ' . ($gp2->rating_deviation > 0 ? '+' . $gp2->rating_deviation : $gp2->rating_deviation);
                                                    // $mp3 = $player3->name . ' ' . ($gp3->rating_deviation > 0 ? '+' . $gp3->rating_deviation : $gp3->rating_deviation);
                                                    // $mp4 = $player4->name . ' ' . ($gp4->rating_deviation > 0 ? '+' . $gp4->rating_deviation : $gp4->rating_deviation);

                                                    // $mp2 = $player2->name . ' (' . $gp2->rating_deviation . ')';
                                                    // $mp3 = $player3->name . ' (' . $gp3->rating_deviation . ')';
                                                    // $mp4 = $player4->name . ' (' . $gp4->rating_deviation . ')';

                                                    $match = autoPad('[ ' . $mp1 . ' x ' . $mp2 . ' ]', 32) . ' DEFEATS ' . autoPad('[ ' . $mp3 . ' x ' . $mp4 . ' ]', 32);

                                                    // $match =

                                                @endphp
                                                <div style="font-family: monospace"> Game <?php            echo autoPad($i--, 2); ?> : {!! $match !!}
                                                </div>
                                        @endforeach
                            @endforeach
                @endif
            </div>
            <div style="display: table-cell;font-family: monospace;">
                <code>Season Ratings</code><br>
                <table style=" text-align:center;">
                    <thead>
                        <tr>
                            <td style="width:20px;">#</td>
                            <td style="width:100px;">Player</td>
                            <td style="width:60px;text-align:right;">Rating</td>
                            <td style="width:40px;padding-left:10px">Win</td>
                            <td style="width:40px;">Lose</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($data['players'] as $player)
                                                @php
                                                    $season = $data['season'];
                                                    $gp = App\Models\GamePlayer::wherePlayerId($player->id)->where('season_id', @$season->id)->get();
                                                    $rating = $player->rating()->where('season_id', @$season->id)->first();
                                                    if (!@$rating->value)
                                                        continue;
                                                @endphp
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td> {{ $player->name }}</td>
                                                    <td style="text-align:right;"> {{ @$rating->value }}</td>
                                                    <td> {{ $gp->where('result', 'Win')->count() }}</td>
                                                    <td> {{ $gp->where('result', 'Lose')->count() }}</td>
                                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.create-event').click(function () {
        let event_name = prompt("Event Name : ");
        if (event_name != null) {
            $.post(`{{ route('season.event.create') }}`, {
                event_name: event_name,
                season: `{{ request()->season }}`
            }).done(function (data) {
                console.log(data);
            })
        }
    });
</script>

</html>