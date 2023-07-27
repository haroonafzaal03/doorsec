<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteColumnToStaffDisciplinaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_disciplinaries', function (Blueprint $table) {
            $table->bigInteger('created_by');
            $table->enum('is_deleted',array('0','1'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_disciplinaries', function (Blueprint $table) {
            //
        });
    }
}
