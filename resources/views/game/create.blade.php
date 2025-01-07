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

<body class="font-sans antialiased dark:bg-black dark:text-white/50">

    <select class="player1" size="25">
        @foreach ($data['players']->sortBy('name') as $player)
            <option value="{{ $player->id }}">{{ $player->name }}</option>
        @endforeach
    </select>
    <select class="player2" size="25">
        @foreach ($data['players']->sortBy('name') as $player)
            <option value="{{ $player->id }}">{{ $player->name }}</option>
        @endforeach
    </select>
    VS
    <select class="player3" size="25">
        @foreach ($data['players']->sortBy('name') as $player)
            <option value="{{ $player->id }}">{{ $player->name }}</option>
        @endforeach
    </select>
    <select class="player4" size="25">
        @foreach ($data['players']->sortBy('name') as $player)
            <option value="{{ $player->id }}">{{ $player->name }}</option>
        @endforeach
    </select>
    <a href="#" class="create-game"><code>CREATE</code></a>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.create-game').click(function () {
        let players = [
            $('.player1').val(),
            $('.player2').val(),
            $('.player3').val(),
            $('.player4').val(),
        ]
        $.post(`{{ route('game.store') }}`, {
            players: players,
            season: `{{ request()->season }}`
        }).done(function (data) {
            window.location = "/?season={{ request()->season }}";
        })
    });
</script>

</html>