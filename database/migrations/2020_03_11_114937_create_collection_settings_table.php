<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCollectionSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('day');
            $table->time('collection_time');
            $table->time('delivery_time');
            $table->tinyInteger('arrive_window')->default(30);
            $table->tinyInteger('depart_window')->default(15);
            $table->string('collection_route', 20);
            $table->string('delivery_route', 20);
            $table->integer('company_id')->unsigned()->index('collection_settings_company_id_foreign');
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
        Schema::drop('collection_settings');
    }
}
