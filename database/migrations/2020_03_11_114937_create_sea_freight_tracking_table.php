<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeaFreightTrackingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sea_freight_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 100)->nullable();
            $table->string('status_name', 100)->nullable();
            $table->string('message')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->integer('user_id')->unsigned()->comment('Link to the users table - populated with a user ID if a manual tracking event added by IFS operator');
            $table->integer('sea_freight_shipment_id')->unsigned()->index('sea_freight_tracking_sea_freight_shipment_id_foreign');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sea_freight_tracking');
    }
}
