<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScanningKpisTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scanning_kpis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expected');
            $table->integer('collection');
            $table->integer('receipt');
            $table->integer('route');
            $table->integer('receipt_missed');
            $table->integer('route_missed');
            $table->timestamp('date')->nullable();
            $table->timestamps();
            $table->decimal('collection_percentage', 13, 1)->nullable();
            $table->decimal('receipt_percentage', 13, 1)->nullable();
            $table->decimal('route_percentage', 13, 1)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scanning_kpis');
    }
}
