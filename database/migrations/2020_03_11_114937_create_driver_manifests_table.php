<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverManifestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_manifests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 20);
            $table->boolean('closed')->comment('Flag indicating manifest is closed');
            $table->integer('depot_id')->unsigned()->index('driver_manifests_depot_id_foreign')->comment('Link to the depots table');
            $table->integer('driver_id')->unsigned()->index('driver_manifests_driver_id_foreign')->comment('Link to the drivers table');
            $table->integer('vehicle_id')->unsigned()->index('driver_manifests_vehicle_id_foreign')->comment('Link to the vehicles table');
            $table->timestamp('date')->nullable();
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
        Schema::drop('driver_manifests');
    }
}
