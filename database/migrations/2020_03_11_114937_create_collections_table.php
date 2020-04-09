<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCollectionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pieces')->unsigned()->comment('The number of packages (pieces) in the consignment.');
            $table->decimal('weight', 13)->nullable()->comment('The weight of the consignment in kg');
            $table->decimal('volumetric_weight', 13)->nullable()->comment('The volumetric weight of the consignment');
            $table->string('collection_instructions', 100)->nullable()->comment('Special instructions for the driver');
            $table->string('delivery_instructions', 100)->nullable()->comment('Special instructions for the driver');
            $table->string('scs_job_number', 15)->nullable()->comment('SCS job number to link in with SCS');
            $table->char('collection_type', 1)->nullable()->comment('Type of collection address - (r)esidential or (c)ommercial');
            $table->string('collection_name', 50)->nullable()->comment('The name of the collection');
            $table->string('collection_company_name', 100)->nullable()->comment('Collection company');
            $table->string('collection_address1')->nullable()->comment('Collection address line 1');
            $table->string('collection_address2')->nullable()->comment('Collection address line 2');
            $table->string('collection_address3')->nullable()->comment('Collection address line 3');
            $table->string('collection_city', 50)->nullable()->comment('Collection city');
            $table->string('collection_state', 50)->nullable()->comment('Collection state');
            $table->string('collection_postcode', 8)->nullable()->comment('Collection postcode');
            $table->char('collection_country_code', 2)->nullable()->comment('Collection country code');
            $table->string('collection_telephone', 15)->nullable()->comment('Collection telephone number');
            $table->string('collection_email', 100)->nullable()->comment('Collection email address');
            $table->char('delivery_type', 1)->nullable()->comment('Type of delivery address - (r)esidential or (c)ommercial');
            $table->string('delivery_name', 50)->nullable()->comment('The name of the delivery');
            $table->string('delivery_company_name', 100)->nullable()->comment('Deliveries company name');
            $table->string('delivery_address1')->nullable()->comment('Deliveries address line 1');
            $table->string('delivery_address2')->nullable()->comment('Deliveries address line 2');
            $table->string('delivery_address3')->nullable()->comment('Deliveries address line 3');
            $table->string('delivery_city', 50)->nullable()->comment('Deliveries city');
            $table->string('delivery_state', 50)->nullable()->comment('Deliveries state');
            $table->string('delivery_postcode', 8)->nullable()->comment('Deliveries postcode');
            $table->char('delivery_country_code', 2)->nullable()->comment('Deliveries Country Code');
            $table->string('delivery_telephone', 12)->nullable()->comment('Deliveries Telephone');
            $table->string('delivery_email', 100)->nullable()->comment('Deliveries Email');
            $table->integer('user_id')->unsigned()->index('collections_user_id_foreign')->comment('Link to the users table');
            $table->integer('company_id')->unsigned()->index('collections_company_id_foreign')->comment('Link to the companies table');
            $table->integer('status_id')->unsigned()->nullable()->index('collections_status_id_foreign')->comment('Link to the statuses table');
            $table->integer('depot_id')->unsigned()->index('collections_depot_id_foreign')->comment('Link to the depots table');
            $table->integer('driver_manifest_id')->unsigned()->nullable()->comment('Link to the driver manifests table');
            $table->timestamp('collection_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Date that the customer wants the consignment to be collected');
            $table->timestamp('delivery_date')->default('0000-00-00 00:00:00')->comment('Date and time delivery required');
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
        Schema::drop('collections');
    }
}
