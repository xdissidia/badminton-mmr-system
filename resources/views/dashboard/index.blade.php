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
        font-family: "monospace" !important
    }
</style>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div style="width: 100%; display: table;">
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">
                <code><a href="#" class='create-event'>{{ $data['season']->name }}</a></code>
                <br>
                @foreach ($data['season']->events as $event)
                    <code><a href="{{ route('game.create') }}" class='create-game'>{{ $event->name }}</a></code>
                    <br>
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

                            // $mp1 = $player1->name . ' ' . ($gp1->rating_deviation > 0 ? '+' . $gp1->rating_deviation : $gp1->rating_deviation);
                            // $mp2 = $player2->name . ' ' . ($gp2->rating_deviation > 0 ? '+' . $gp2->rating_deviation : $gp2->rating_deviation);
                            // $mp3 = $player3->name . ' ' . ($gp3->rating_deviation > 0 ? '+' . $gp3->rating_deviation : $gp3->rating_deviation);
                            // $mp4 = $player4->name . ' ' . ($gp4->rating_deviation > 0 ? '+' . $gp4->rating_deviation : $gp4->rating_deviation);

                            // $mp2 = $player2->name . ' (' . $gp2->rating_deviation . ')';
                            // $mp3 = $player3->name . ' (' . $gp3->rating_deviation . ')';
                            // $mp4 = $player4->name . ' (' . $gp4->rating_deviation . ')';

                            $match = '[ ' . $mp1 . ' x ' . $mp2 . ' ] DEFEATS [ ' . $mp3 . ' x ' . $mp4 . ' ]';
                        @endphp
                        <code>Game {!! str_pad($i--, 2, " ", STR_PAD_LEFT) !!} : {{ $match }}</code>
                        <br>
                    @endforeach
                @endforeach
            </div>
            <div style="display: table-cell;font-family: monospace;">
                <code>Player Ratings</code><br>
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
                                $gp = App\Models\GamePlayer::wherePlayerId($player->id)->get();
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td> {{ $player->name }}</td>
                                <td style="text-align:right;"> {{ @$player->rating->value }}</td>
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

    $('.create-event').click(function() {
        let event_name = prompt("Event Name : ");
        if (event_name != null) {
            $.post(`{{ route('season.event.create') }}`, {
                event_name: event_name
            }).done(function(data) {
                console.log(data);
            })
        }
    });
</script>

</html>
