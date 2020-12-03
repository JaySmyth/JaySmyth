<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtdLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etd_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('attempts')->default(0);
            $table->boolean('document_created')->default(0);
            $table->boolean('uploaded')->default(0);
            $table->text('response')->nullable();
            $table->boolean('success')->default(0);
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
        Schema::dropIfExists('etd_logs');
    }
}
