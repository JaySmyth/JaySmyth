<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeadingRowToFileUploadHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_upload_hosts', function (Blueprint $table) {
            $table->boolean('heading_row')->after('csv_delimiter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_upload_hosts', function (Blueprint $table) {
            $table->dropColumn('heading_row');
        });
    }
}
