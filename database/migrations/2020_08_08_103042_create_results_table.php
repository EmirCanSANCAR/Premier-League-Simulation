<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->unsignedTinyInteger('week');
            $table->unsignedBigInteger('home_football_club_id');
            $table->unsignedBigInteger('home_football_club_goal_count')->nullable();
            $table->unsignedBigInteger('away_football_club_id');
            $table->unsignedBigInteger('away_football_club_goal_count')->nullable();
            $table->timestamps();

            $table->primary(['home_football_club_id', 'away_football_club_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
