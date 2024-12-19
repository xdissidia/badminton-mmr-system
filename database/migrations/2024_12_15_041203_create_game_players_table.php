<?php

use App\Models\EventGame;
use App\Models\GameTeam;
use App\Models\Player;
use App\Models\Season;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Season::class)->nullable();
            $table->foreignIdFor(EventGame::class);
            $table->tinyInteger('player_number');
            $table->set('team', ['Team 1', 'Team 2']);
            $table->set('result', ['Win', 'Lose'])->nullable();
            $table->foreignIdFor(Player::class)->nullable();
            $table->integer('rating_current')->nullable();
            $table->integer('rating_deviation')->nullable();
            $table->integer('rating_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
