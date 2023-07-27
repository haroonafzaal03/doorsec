<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffDisciplinariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_disciplinaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('staff_id');
            $table->string('letter_type')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_path')->nullable();
            $table->longText('admin_notes')->nullable();
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
        Schema::dropIfExists('staff_disciplinaries');
    }
}
