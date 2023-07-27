<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('day_start_time');
            $table->time('day_end_time');
            $table->integer('require_staff_day');
            $table->time('night_start_time');
            $table->time('night_end_time');
            $table->integer('require_staff_night');
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
        Schema::dropIfExists('guardings');
    }
}
