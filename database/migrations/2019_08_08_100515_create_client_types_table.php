<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('client_types')){
            Schema::create('client_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type')->null();
                $table->enum('status',array('1','0'));
                $table->timestamps();
            });

            DB::table('client_types')->insert([
                ['type' => 'Permanent Club', 'status' => 1],
                ['type' => 'Events', 'status' => 1]
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
        Schema::dropIfExists('client_types');
    }
}
