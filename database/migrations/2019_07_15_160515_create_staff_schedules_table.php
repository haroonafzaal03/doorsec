<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('schedule_id')->nullable();
            $table->integer('event_id')->nullable();
            $table->bigInteger('venue_id')->nullable();
            $table->bigInteger('venue_detail_id')->nullable();
            $table->bigInteger('guarding_id')->nullable();
            $table->string('shift_type')->nullable();
            $table->bigInteger('staff_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->double('hours')->nullable();
            $table->bigInteger('assignment_type')->nullable();
            $table->date('day');
            $table->tinyInteger('availability')->nullable();
            $table->double('rate_per_hour');
            $table->tinyInteger('is_payroll_active')->default(0);
            $table->string('wa_response')->nullable();
            $table->string('message_id')->nullable();
            $table->string('updated_by')->nullable();
            $table->enum('status', array('pending', 'confirmed', 'dropout'))->default('pending');
            $table->enum('sms_status', array('pending','not_sent','confirmed','declined','incorrect_number'))->default('pending');
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('staff_schedules');
    }
}
