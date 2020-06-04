<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFileUploadHostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_upload_hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->integer('port')->unsigned();
            $table->longText('private_key')->nullable()->comment('path/to/or/contents/of/privatekey');
            $table->string('directory')->nullable()->comment('Path to root');
            $table->integer('timeout')->unsigned()->nullable();
            $table->string('directory_permissions')->nullable();
            $table->boolean('passive')->comment('FTP setting only');
            $table->boolean('sftp')->comment('SFTP host (ftp if false)');
            $table->char('csv_delimiter', 1)->nullable();
            $table->boolean('heading_row');
            $table->integer('company_id')->unsigned()->index('file_upload_hosts_company_id_foreign')->comment('Link to the companies table');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_upload_hosts');
    }
}
