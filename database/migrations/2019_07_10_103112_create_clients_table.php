<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_type_id')->nullable();
            $table->string('property_name')->nullable();
            $table->string('property_lice_name')->nullable();
            $table->bigInteger('property_lice_number')->nullable();
            $table->date('property_lice_expiry_date')->nullable();
            $table->bigInteger('property_tax_regis_num')->nullable();
            $table->string('property_signatory_id')->nullable();
            $table->date('property_contract_start')->nullable();
            $table->date('property_contract_end')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_logo')->nullable();
            $table->string('tarde_lice')->nullable();
            $table->string('venue_manager_name')->nullable();
            $table->bigInteger('venue_manager_number')->nullable();
            $table->string('venue_manager_email')->nullable();
            $table->string('account_manager_name')->nullable();
            $table->string('account_manager_email')->nullable();
            $table->bigInteger('account_manager_num')->nullable();
            $table->enum('status',array('active','pending','blocked','deleted'))->nullable()->default('active');
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
        Schema::dropIfExists('clients');
    }
}
