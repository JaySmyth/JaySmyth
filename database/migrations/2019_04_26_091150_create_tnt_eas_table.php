<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTntEasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tnt_eas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_code',2)->index();
            $table->string('description',30);
            $table->string('from_postcode',12);
            $table->string('to_postcode',12);
            $table->index(['country_code', 'from_postcode', 'to_postcode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tnt_eas');
    }
}
