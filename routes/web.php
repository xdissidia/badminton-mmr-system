<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MatchMakerController;
use App\Http\Controllers\SeasonEventController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware([])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::post('/season/event/create', [SeasonEventController::class, 'eventCreate'])->name('season.event.create');
    Route::get('/game/create', [GameController::class, 'create'])->name('game.index');
    Route::post('/game/create', [GameController::class, 'store'])->name('game.store');
    Route::get('/match/maker', [MatchMakerController::class, 'index'])->name('match.maker');
    Route::get('/match/maker/get', [MatchMakerController::class, 'getMatchMakerMatches'])->name('match.maker.get');
    Route::post('/match/maker/update', [MatchMakerController::class, 'updateMatchMakerMatches'])->name('match.maker.update');
    Route::post('/match/maker', [MatchMakerController::class, 'createMatches'])->name('match.maker.create');
});
