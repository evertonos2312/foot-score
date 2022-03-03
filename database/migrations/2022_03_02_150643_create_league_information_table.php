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
        Schema::create('league_information', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('logo');
            $table->string('country_name');
            $table->string('year_current_season');
            $table->date('season_start');
            $table->date('season_end');
            $table->boolean('current');
            $table->string('current_round');
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
        Schema::dropIfExists('league_information');
    }
};
