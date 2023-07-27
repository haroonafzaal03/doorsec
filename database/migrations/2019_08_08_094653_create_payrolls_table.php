<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_sch_id')->nullable();
            $table->bigInteger('event_id')->nullable();
            $table->bigInteger('venue_id')->nullable();
            $table->bigInteger('staff_id')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('pending_amount')->nullable();
            $table->enum('payment_status',array('paid','unpaid','hold','partial'))->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('staff_status',array('pending','confirmed','dropout'))->nullalble();
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
        Schema::dropIfExists('payrolls');
    }
}
