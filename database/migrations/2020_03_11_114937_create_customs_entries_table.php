<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomsEntriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customs_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 20)->nullable()->comment('Entry number');
            $table->string('reference', 30)->comment('Customers reference for the shipment');
            $table->string('additional_reference', 30)->nullable()->comment('Customers additional reference for the shipment');
            $table->string('consignment_number', 30)->comment('Consignment number related');
            $table->string('scs_job_number', 15)->nullable()->comment('SCS job number to link in with SCS');
            $table->decimal('commercial_invoice_value', 13)->nullable()->comment('The value of the shipment');
            $table->char('commercial_invoice_value_currency_code', 3)->nullable()->comment('The currency to be used for  the consignment value. For example GBP, USD');
            $table->decimal('customs_value', 13)->nullable()->comment('The customs value of the shipment');
            $table->integer('pieces')->unsigned()->nullable()->comment('The number of pieces');
            $table->decimal('weight', 13)->nullable()->comment('The weight of the consignment in kg');
            $table->integer('commodity_count')->unsigned()->nullable()->comment('The number of commodities');
            $table->char('country_of_origin', 2)->nullable();
            $table->integer('company_id')->unsigned()->index('customs_entries_company_id_foreign')->comment('Link to the companies table');
            $table->integer('user_id')->unsigned()->nullable()->index('customs_entries_user_id_foreign')->comment('Link to the users table');
            $table->timestamp('date')->nullable()->comment('Date entry raised with customs');
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
        Schema::drop('customs_entries');
    }
}
