<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGudardingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gudarding_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('schedule_id');
            $table->bigInteger('staff_id');
            $table->bigInteger('guarding_id');
            $table->text('day')->nullable();
            $table->text('night')->nullable();
            $table->text('afternoon')->nullable();
            $table->text('late_day')->nullable();
            $table->text('evening')->nullable();
            $table->text('absent')->nullable();
            $table->text('sick_leave')->nullable();
            $table->text('annual_leave')->nullable();
            $table->text('emergency_leave')->nullable();
            $table->text('unpaid_leave')->nullable();
            $table->text('day_off')->nullable();
            $table->text('off_working_night')->nullable();
            $table->text('off_working_day')->nullable();
            $table->text('training')->nullable();
            $table->text('overtime')->nullable();
            $table->text('event_day')->nullable();
            $table->text('public_holiday')->nullable();
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
        Schema::dropIfExists('gudarding_schedules');
    }
}