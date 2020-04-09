<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrackingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('status_detail', 50)->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamp('local_datetime')->nullable()->comment('Unaltered timestamp');
            $table->string('carrier', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country_code', 50)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('tracker_id', 100)->nullable();
            $table->string('source', 30)->nullable()->comment('Where the tracking event originated from - e.g. easypost or IFS depot');
            $table->timestamp('estimated_delivery_date')->nullable();
            $table->timestamp('local_estimated_delivery_date')->nullable()->comment('Unaltered timestamp');
            $table->integer('user_id')->unsigned()->comment('Link to the users table - populated with a user ID if a manual tracking event added by IFS operator');
            $table->integer('shipment_id')->unsigned()->index('tracking_shipment_id_foreign');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tracking');
    }
}
