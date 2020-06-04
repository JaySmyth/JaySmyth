<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseInvoiceLinesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shipment_reference', 30)->nullable();
            $table->string('carrier_consignment_number', 30)->nullable();
            $table->string('carrier_tracking_number', 30)->nullable();
            $table->integer('pieces')->unsigned()->nullable();
            $table->decimal('weight', 13)->nullable();
            $table->string('weight_uom', 15)->nullable();
            $table->decimal('billed_weight', 13)->nullable();
            $table->decimal('length', 13)->nullable();
            $table->decimal('width', 13)->nullable();
            $table->decimal('height', 13)->nullable();
            $table->string('dims_uom', 4)->nullable()->comment('Default is cm');
            $table->integer('volumetric_divisor')->unsigned()->nullable();
            $table->decimal('value', 13)->nullable();
            $table->string('value_currency_code', 3)->nullable();
            $table->string('carrier_service', 100)->nullable();
            $table->string('carrier_packaging_code', 15)->nullable();
            $table->string('carrier_pay_code')->nullable();
            $table->string('sender_name', 50)->nullable();
            $table->string('sender_company_name')->nullable();
            $table->string('sender_address1')->nullable();
            $table->string('sender_address2')->nullable();
            $table->string('sender_city', 50)->nullable();
            $table->string('sender_state', 50)->nullable();
            $table->string('sender_postcode', 12)->nullable();
            $table->string('sender_country_code', 2)->nullable();
            $table->string('sender_account_number', 12)->nullable();
            $table->string('recipient_name', 50)->nullable();
            $table->string('recipient_company_name')->nullable();
            $table->string('recipient_address1')->nullable();
            $table->string('recipient_address2')->nullable();
            $table->string('recipient_city', 50)->nullable();
            $table->string('recipient_state', 50)->nullable();
            $table->string('recipient_postcode', 12)->nullable();
            $table->string('recipient_country_code', 2)->nullable();
            $table->string('recipient_account_number', 12)->nullable();
            $table->string('pod_signature', 50)->nullable();
            $table->string('account_number1', 12)->nullable();
            $table->string('account_number2', 12)->nullable();
            $table->string('scs_job_number', 15)->nullable();
            $table->integer('user_id')->unsigned()->comment('The user that passes the line');
            $table->integer('shipment_id')->unsigned()->nullable()->comment('Link to shipments table');
            $table->integer('purchase_invoice_id')->unsigned()->index('purchase_invoice_lines_purchase_invoice_id_foreign');
            $table->timestamp('ship_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
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
        Schema::drop('purchase_invoice_lines');
    }
}
