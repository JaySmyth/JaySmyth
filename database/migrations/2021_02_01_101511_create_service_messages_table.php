<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->string('title', 100);
            $table->text('message', 65535);
            $table->boolean('sticky');
            $table->boolean('enabled');
            $table->boolean('ifs_only');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
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
        Schema::dropIfExists('service_messages');
    }
}
