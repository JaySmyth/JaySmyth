<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 10)->comment('IFS code for this service');
            $table->string('name', 50)->comment('IFS name for this service');
            $table->string('carrier_code', 10)->comment('Carriers code for this service');
            $table->string('carrier_name', 50)->comment('Carriers name for this service');
            $table->string('transend_route', 5)->nullable();
            $table->string('account', 12)->comment('Default carrier account number to be billed');
            $table->string('scs_account_code', 7)->nullable();
            $table->string('scs_job_route', 10)->nullable();
            $table->integer('volumetric_divisor')->unsigned()->comment('The volumetric divisor used for this service');
            $table->mediumText('parameters')->nullable()->comment('Additional carrier specific paramaters saved as json');
            $table->boolean('default')->comment('Defines if this is a default service');
            $table->string('sender_country_codes', 100)->comment('A list of viable sender countries - null for all');
            $table->string('recipient_country_codes', 100)->comment('A list of viable recipient countries - null for all');
            $table->string('sender_postcode_regex', 60)->comment('Regular expression defining the expected sender postcode');
            $table->string('recipient_postcode_regex', 60)->comment('Regular expression defining the expected recipient postcode');
            $table->string('account_number_regex', 60)->comment('Regular expression defining the expected billing account number format');
            $table->decimal('min_weight', 10)->comment('The minimum weight supported on this service');
            $table->decimal('max_weight', 10)->comment('The maximum weight supported on this service');
            $table->integer('max_pieces')->unsigned()->comment('The maximum number of pieces supported by this service');
            $table->integer('max_dimension')->unsigned()->comment('The maximum dimension supported by this service');
            $table->integer('max_girth')->default(0);
            $table->integer('max_customs_value')->unsigned()->comment('The maxium customs value supported by this service');
            $table->string('packaging_types')->comment('List of packaging types supported by this service - leave null if no restriction');
            $table->boolean('hazardous')->comment('Indicates if this service supports hazardous shipments');
            $table->boolean('dry_ice')->comment('Indicates if this service supports dry ice shipments');
            $table->boolean('alcohol')->comment('Indicates if this service supports alcohol shipments');
            $table->boolean('broker')->comment('Indicates if this service supports using a broker');
            $table->boolean('doc')->comment('Indicates servce is valid for document shipments');
            $table->boolean('nondoc')->comment('Indicates servce is valid for non document shipments (non EU)');
            $table->boolean('eu')->comment('Indicates service is valid for shipments between EU countries');
            $table->boolean('non_eu')->comment('Indicates service is valid for shipments involving one or more Non EU country');
            $table->boolean('9am')->comment('Indicates if this service timed delivery by 9am');
            $table->boolean('1030am')->comment('Indicates if this service timed delivery by 1030am');
            $table->boolean('12pm')->comment('Indicates if this service timed delivery by 12pm');
            $table->boolean('lithium_batteries')->default(0);
            $table->integer('carrier_id')->unsigned()->comment('Link to the carrier table (FedEx, DHL, UPS etc.)');
            $table->integer('mode_id')->unsigned()->comment('Link to the modes of transport table (courier, air etc.)');
            $table->integer('depot_id')->unsigned()->comment('Link to the depot table (Antrim, London, ECX, NYC etc.)');
            $table->integer('cost_rate_id')->unsigned()->comment('Link to the Rate table for cost rate');
            $table->integer('costs_surcharge_id')->default(0);
            $table->integer('sales_surcharge_id')->default(0);
            $table->boolean('allow_zero_cost');
            $table->integer('sales_rate_id')->unsigned()->comment('Link to the Rate table for sales rate');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('services');
    }
}
