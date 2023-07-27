<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('staff_type_id')->nullable();
            $table->string('contact_number')->nullable();
            $table->bigInteger('basic_salary')->nullable();
            $table->bigInteger('other_contact_number')->nullable();
            $table->string('picture',255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender',50)->nullable();
            $table->string('edu_document',255)->nullable();
            $table->string('contact_number_home')->nullable();
            $table->string('next_to_kin',255)->nullable();
            $table->string('passport_number',255)->nullable();
            $table->string('emitrates_id',255)->nullable();
            $table->string('emirated_id_attach',255)->nullable();
            $table->date('emirates_expiry')->nullable();
            $table->date('noc_expiry')->nullable();
            $table->string('noc_attach',255)->nullable();
            $table->string('uid_number',255)->nullable();
            $table->string('sira_id_number',255)->nullable();
            $table->string('sira_id_attach',255)->nullable();
            $table->integer('sira_type_id')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->date('passport_issue')->nullable();
            $table->date('visa_expiry')->nullable();
            $table->string('sponsor_details',255)->nullable();
            $table->string('nationality')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('passport_attach')->nullable();
            $table->string('nk_name')->nullable();
            $table->string('nk_relation')->nullable();
            $table->string('nk_phone')->nullable();
            $table->string('nk_address')->nullable();
            $table->string('nk_nationality')->nullable();
            $table->longText('block_for_clients')->nullable();
            $table->tinyInteger('is_super_staff')->default(0);
            $table->enum('status',array('active','deactivate'));
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
        Schema::dropIfExists('staff');
    }
}
