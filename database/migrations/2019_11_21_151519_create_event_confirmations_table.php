<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_confirmations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_id')->nullable();
            $table->bigInteger('event_id')->nullable();
            $table->bigInteger('venue_id')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('location')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('briefing')->nullable();
            $table->string('venue')->nullable();
            $table->string('location_guide')->nullable();
            $table->string('dress_code')->nullable();
            $table->string('start_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('date')->nullable();
            $table->enum('status',array('sent', 'confirmed', 'pending', 'declined'))->nullable();
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
        Schema::dropIfExists('event_confirmations');
    }
}
