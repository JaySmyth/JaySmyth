<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurrenciesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->char('code', 3)->unique();
            $table->string('currency', 50)->index();
            $table->integer('display_order')->unsigned();
            $table->increments('id');
            $table->decimal('rate', 13, 4)->nullable()->comment('Exchange rate');
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
        Schema::drop('currencies');
    }
}
