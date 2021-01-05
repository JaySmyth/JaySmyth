<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepricingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repricing_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('original_shipping_charge');
            $table->decimal('original_shipping_cost');
            $table->text('original_quoted');
            $table->decimal('new_shipping_charge');
            $table->decimal('new_shipping_cost');
            $table->text('new_quoted');
            $table->bigInteger('shipment_id');
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
        Schema::dropIfExists('repricing_logs');
    }
}
