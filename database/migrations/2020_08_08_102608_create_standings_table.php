<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->unsignedBigInteger('football_club_id')->primary();
            $table->integer('pts')
                ->default(0)
                ->comment('Points (i.e., total number of points earned by a team after playing a certain number of games)');
            $table->integer('p')
                ->default(0)
                ->comment('Played (i.e., number of matches or games played by a team)');
            $table->integer('w')
                ->default(0)
                ->comment('Won (i.e., number of matches won)');
            $table->integer('d')
                ->default(0)
                ->comment('Drawnn (i.e., number of times a team has finished a match with an even score or tie)');
            $table->integer('l')
                ->default(0)
                ->comment('Loss (i.e., number of matches lost)');
            $table->integer('gd')
                ->default(0)
                ->comment('Goal Difference (i.e., difference between GF and GA, and sometimes denoted by +/-)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standings');
    }
}
