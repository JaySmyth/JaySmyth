<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFileUploadLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('file_upload_logs', function(Blueprint $table)
		{
			$table->foreign('file_upload_id')->references('id')->on('file_uploads')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('file_upload_logs', function(Blueprint $table)
		{
			$table->dropForeign('file_upload_logs_file_upload_id_foreign');
		});
	}

}
