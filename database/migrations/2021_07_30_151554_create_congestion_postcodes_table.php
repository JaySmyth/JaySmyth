<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongestionPostcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('congestion_postcodes', function (Blueprint $table) {
            $table->id();
            $table->integer('carrier_id');
            $table->string('from_postcode', 10);
            $table->string('to_postcode', 10);
            $table->string('charge_type', 5);
            $table->date('from_date');
            $table->date('to_date');
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
        Schema::dropIfExists('congestion_postcodes');
    }
}
