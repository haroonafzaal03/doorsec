<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiraTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sira_types')){
            Schema::create('sira_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type')->null();
                $table->enum('status', array('1', '0'));
                $table->timestamps();
            });

            // Insert some stuff
            DB::table('sira_types')->insert([
                ['type' => 'Security Guard', 'status' => 1],
                ['type' => 'CCTV', 'status' => 1],
                ['type' => 'Supervisor', 'status' => 1],
                ['type' => 'Security Manager', 'status' => 1],
                ['type' => 'Event Security', 'status' => 1],
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
        Schema::dropIfExists('sira_types');
    }
}
