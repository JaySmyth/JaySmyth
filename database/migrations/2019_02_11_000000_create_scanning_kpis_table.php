<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->timestamp('date');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('scanning_kpis');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
