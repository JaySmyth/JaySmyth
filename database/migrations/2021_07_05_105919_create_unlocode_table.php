<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnlocodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unlocodes', function (Blueprint $table) {
            $table->id();
            $table->string('change_indicator', 1);
            $table->string('country_code', 2);
            $table->string('location', 3);
            $table->string('name', 100);
            $table->string('name_plain', 100);
            $table->string('sub_division', 3);
            $table->string('status', 12);
            $table->string('function', 10);
            $table->string('last_updated', 4);
            $table->string('iata_code', 4);
            $table->string('coord', 16);
            $table->string('remarks', 50);
            $table->timestamps();

            $table->index(['country_code', 'location']);
            $table->index(['country_code', 'name']);
            $table->index(['country_code', 'name_plain']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unlocodes');
    }
}
