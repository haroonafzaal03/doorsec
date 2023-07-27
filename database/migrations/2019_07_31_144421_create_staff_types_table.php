<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('staff_types')){
            Schema::create('staff_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type')->null();
                $table->string('dashboard')->null();
                $table->enum('status',array('1','0'));
                $table->timestamps();
            });
            DB::table('staff_types')->insert([
                ['type' => 'DoorSec Staff','dashboard'=>'club_events', 'status' => 1],
                ['type' => 'Freelancer','dashboard'=>'club_events', 'status' => 1],
                ['type' => 'Guard','dashboard'=>'guarding', 'status' => 1]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_types');
    }
}
