<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_standings', function (Blueprint $table) {
            $table->id();
            $table->integer('league_id');
            $table->string('season');
            $table->integer('rank');
            $table->integer('team_id');
            $table->string('team_name');
            $table->string('team_logo');
            $table->integer('points');
            $table->integer('goalsDiff');
            $table->string('form');
            $table->integer('played');
            $table->integer('win');
            $table->integer('draw');
            $table->integer('lose');
            $table->integer('goals_for');
            $table->integer('goals_against');
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
        Schema::dropIfExists('league_standings');
    }
};
