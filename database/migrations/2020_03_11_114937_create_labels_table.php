<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLabelsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('base64')->comment('Base64 representation of label');
            $table->boolean('archived')->comment('Boolean indicating if the pdf has been archived to S3');
            $table->integer('shipment_id')->unsigned()->index('labels_shipment_id_foreign')->comment('Link to the shipments table');
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
        Schema::drop('labels');
    }
}
