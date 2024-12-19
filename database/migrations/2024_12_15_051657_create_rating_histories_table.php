<?php

use App\Models\SeasonPlayerRating;
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
        Schema::create('rating_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SeasonPlayerRating::class);
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
        Schema::dropIfExists('rating_histories');
    }
};
