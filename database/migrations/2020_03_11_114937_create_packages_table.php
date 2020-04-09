<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('index')->unsigned();
            $table->integer('length');
            $table->integer('width');
            $table->integer('height');
            $table->decimal('weight', 5);
            $table->decimal('volumetric_weight', 5);
            $table->decimal('dry_ice_weight', 5);
            $table->string('packaging_code', 10);
            $table->string('carrier_packaging_code', 10);
            $table->string('carrier_tracking_number');
            $table->string('barcode')->index();
            $table->boolean('collected')->nullable()->default(0);
            $table->boolean('received')->default(0);
            $table->boolean('loaded')->nullable()->default(0);
            $table->string('location', 15);
            $table->integer('shipment_id')->unsigned()->index('packages_shipment_id_foreign');
            $table->timestamp('date_collected')->nullable();
            $table->timestamp('date_received')->nullable()->comment('The date/time the package was received by IFS');
            $table->timestamp('date_loaded')->nullable();
            $table->timestamps();
            $table->integer('supplied_length')->nullable();
            $table->integer('supplied_width')->nullable();
            $table->integer('supplied_height')->nullable();
            $table->decimal('supplied_weight', 13)->nullable();
            $table->decimal('supplied_volumetric_weight', 13)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packages');
    }
}
