<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('guardings', function (Blueprint $table) {
        //     //
        //     $table->index('client_id');
        // });
        // Schema::table('staff_schedules', function (Blueprint $table) {
        //     //
        //     $table->index('schedule_id');
        // });
        // Schema::table('sira_types', function (Blueprint $table) {
        //     //
        //     $table->index('assignment_type');
        // });
        // Schema::table('guarding_schedules', function (Blueprint $table) {
        //     //
        //     $table->index('guarding_id');
        //     $table->index('schedule_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('indexing');
    }
}
