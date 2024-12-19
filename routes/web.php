<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SeasonEventController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware([])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::post('/season/event/create', [SeasonEventController::class, 'eventCreate'])->name('season.event.create');

    Route::get('/game/create', [GameController::class, 'create'])->name('game.create');
    Route::post('/game/create', [GameController::class, 'store'])->name('game.create');
});
