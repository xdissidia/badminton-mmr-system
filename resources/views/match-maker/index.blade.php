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

    div,
    table {
        font-family: "monospace";
    }

    .superscript {
        position: relative;
        top: -2px;
        font-size: 80%;
    }

    .player-list {
        width: 200px;
    }
</style>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div style="width: 100%; overflow: hidden;">
        <div style="width: 300px; float: left;">
            <div style="font-family:monospace;">
                <a href="#" class="load-match-btn">LOAD MATCH</a>&nbsp;&nbsp;
                <input id="match_id_load" name="match_id_load" style="width:100px;">
                <br>
                <br>
                <a href="#" class="create-match-btn">CREATE MATCH</a>
                <input id="match_id" name="match_id" style="width:100px;">
                <br>
                <br>
                <a href="#" class="update-match-btn">UPDATE MATCH</a>
                <input id="match_id_update" name="match_id_update" style="width:65px;">
                <input id="game_number_reset" name="game_number_reset" style="width:20px;">
                <br>
                <br>
            </div>
            <div>
                <select id="players" name='players' class="player-list" size="30" multiple>
                    @foreach ($players->sortBy('name') as $player)
                        <option value="{{ $player->id }}">{{ $player->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="">
            <table class="matches-table">
            </table>
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
    $('.update-match-btn').click(function () {
        let players = $("#players").val();
        $.post(`{{route('match.maker.update')}}`, { players: players, match_id: $('#match_id_update').val(), match_reset: $('#game_number_reset').val() }).done(function (d) {
            $('.matches-table').html('')
            let matches = d.matches;
            for (i in matches) {
                let teams = matches[i];
                let vs = [];
                for (n in teams) {
                    let p = teams[n];
                    vs.push(`${p[0]} ${p[1]}`);
                }
                let gn = parseInt(i) + 1;
                let g = `Game ${gn} : `;
                let m = vs.join(' vs ');
                $('.matches-table').append(`<tr style="font-family:monospace;"><td >${g}${m}</td></tr>`)
            }
        })
    })
    $('.create-match-btn').click(function () {
        let players = $("#players").val();
        $.post(`{{route('match.maker.create')}}`, { players: players, match_id: $('#match_id').val() }).done(function (d) {
            $('.matches-table').html('')
            let matches = d.matches;
            for (i in matches) {
                let teams = matches[i];
                let vs = [];
                for (n in teams) {
                    let p = teams[n];
                    vs.push(`${p[0]} ${p[1]}`);
                }
                let gn = parseInt(i) + 1;
                let g = `Game ${gn} : `;
                let m = vs.join(' vs ');
                $('.matches-table').append(`<tr style="font-family:monospace;"><td >${g}${m}</td></tr>`)
            }
        })
    });

    $('.load-match-btn').click(function () {
        $.get(`{{route('match.maker.get')}}?match_id=${$('#match_id_load').val()}`).done(function (d) {
            let matches = d.matches;
            $('.matches-table').html('')
            for (i in matches) {
                let teams = matches[i];
                let vs = [];
                for (n in teams) {
                    let p = teams[n];
                    vs.push(`${p[0]} ${p[1]}`);
                }
                let gn = parseInt(i) + 1;
                let g = `Game ${gn} : `;
                let m = vs.join(' vs ');
                $('.matches-table').append(`<tr><td style="font-family:monospace;">${g}${m}</td></tr>`)
            }
        })
    });
</script>

</html>