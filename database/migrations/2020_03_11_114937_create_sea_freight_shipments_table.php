<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeaFreightShipmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sea_freight_shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 12)->comment('IFS generated shipment number');
            $table->string('reference', 30)->nullable()->comment('Senders reference for the shipment');
            $table->string('final_destination', 100)->nullable();
            $table->char('final_destination_country_code', 2)->nullable()->comment('Destination country code');
            $table->string('port_of_loading', 100)->nullable();
            $table->string('port_of_discharge', 100)->nullable();
            $table->string('bill_of_lading', 50)->nullable();
            $table->string('vessel', 50)->nullable();
            $table->integer('number_of_containers')->unsigned()->comment('The number of containers in the shipment.');
            $table->decimal('weight', 13)->nullable()->comment('The weight of the shipment in kg');
            $table->decimal('value', 13)->nullable()->comment('The value of the shipment');
            $table->char('value_currency_code', 3)->nullable()->comment('The currency to be used for  the shipment value. For example GBP, USD');
            $table->string('special_instructions')->nullable()->comment('Special instructions');
            $table->string('scs_job_number', 15)->nullable()->comment('SCS job number to link in with SCS');
            $table->string('token', 15)->nullable()->comment('A unique token to be passed in tracking URL or other public links');
            $table->boolean('processed');
            $table->boolean('delivered');
            $table->string('pod_signature', 100)->nullable();
            $table->timestamp('required_on_dock_date')->nullable();
            $table->timestamp('estimated_departure_date')->nullable();
            $table->timestamp('departure_date')->nullable();
            $table->timestamp('estimated_arrival_date')->nullable();
            $table->timestamp('arrival_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->timestamps();
            $table->integer('sea_freight_status_id')->unsigned()->nullable()->index('sea_freight_shipments_sea_freight_status_id_foreign')->comment('Link to the statuses table');
            $table->integer('shipping_line_id')->unsigned()->nullable()->index('sea_freight_shipments_shipping_line_id_foreign')->comment('Link to the carriers table');
            $table->integer('depot_id')->unsigned()->index('sea_freight_shipments_depot_id_foreign')->comment('Link to the depots table');
            $table->integer('user_id')->unsigned()->index('sea_freight_shipments_user_id_foreign')->comment('Link to the users table');
            $table->integer('company_id')->unsigned()->index('sea_freight_shipments_company_id_foreign')->comment('Link to the companies table');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sea_freight_shipments');
    }
}
