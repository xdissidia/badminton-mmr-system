<?php

use App\Models\EventGame;
use App\Models\Season;
use App\Models\SeasonEvent;
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
        Schema::create('event_games', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Season::class);
            $table->foreignIdFor(SeasonEvent::class);
            $table->set('winner', ['Team 1', 'Team 2'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_games');
    }
};
