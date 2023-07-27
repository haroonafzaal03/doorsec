<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhatsappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_id')->nullalble();
            $table->bigInteger('event_id')->nullalble();
            $table->bigInteger('venue_id')->nullalble();
            $table->string('message_id')->nullalble();
            $table->string('related_id')->nullalble();
            $table->enum('message_type',array('business','norma'));
            $table->string('message')->nullalble();
            $table->time('start_time')->nullalble();
            $table->date('start_date')->nullalble();
            $table->string('contact_number')->nullalble();
            $table->enum('sent',array('yes','no'))->nullalble();
            $table->enum('receive',array('yes','no'))->nullalble();
            $table->string('timestamp')->nullalble();
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
        Schema::dropIfExists('whatsapps');
    }
}
