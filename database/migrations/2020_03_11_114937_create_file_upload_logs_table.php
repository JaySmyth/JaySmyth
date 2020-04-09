<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFileUploadLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_upload_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('uploaded');
            $table->longText('output')->nullable();
            $table->integer('file_upload_id')->unsigned()->index('file_upload_logs_file_upload_id_foreign')->comment('Link to the file uploads table');
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
        Schema::drop('file_upload_logs');
    }
}
