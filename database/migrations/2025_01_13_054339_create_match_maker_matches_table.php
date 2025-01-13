<?php

use App\Models\MatchMaker;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('match_maker_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MatchMaker::class);
            $table->integer('iteration')->nullable();
            $table->integer('number');
            $table->foreignIdFor(Player::class, 'p1');
            $table->integer('p1_count')->default(0);
            $table->foreignIdFor(Player::class, 'p2');
            $table->integer('p2_count')->default(0);
            $table->foreignIdFor(Player::class, 'p3');
            $table->integer('p3_count')->default(0);
            $table->foreignIdFor(Player::class, 'p4');
            $table->integer('p4_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_maker_matches');
    }
};
