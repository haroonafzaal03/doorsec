<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_id')->nullable();
            $table->string('name')->nullable();
            $table->string('to')->nullable();
            $table->text('action')->nullable();
            $table->longText('responded_by_in')->nullable();
            $table->longText('action_taken')->nullable();
            $table->enum('status',array('red','yellow','green','pink','blue'))->nullalble();
            $table->string('color_code')->nullable();
            $table->time('time');
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
        Schema::dropIfExists('event_logs');
    }
}
