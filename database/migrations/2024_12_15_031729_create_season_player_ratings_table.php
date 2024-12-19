<?php

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
        Schema::create('season_player_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Season::class);
            $table->foreignIdFor(Player::class);
            $table->integer('value');
            $table->float('trueskill_mean');
            $table->float('trueskill_deviation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('season_player_ratings');
    }
};
