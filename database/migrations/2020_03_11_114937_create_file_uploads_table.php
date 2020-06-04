<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFileUploadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_uploads', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 10)->comment('File upload type - POD, delivery exception etc.');
			$table->boolean('verbose')->default(1);
			$table->string('upload_directory', 50)->comment('Directory to upload the file to');
			$table->string('frequency', 20)->comment('Hourly / Daily / Weekly / Monthly');
			$table->string('time', 11)->comment('Time that the file should be uploaded');
			$table->boolean('last_status')->comment('Last upload status');
			$table->dateTime('last_upload')->nullable()->comment('Date/Time last run');
			$table->dateTime('next_upload')->nullable()->comment('Date/Time of next run');
			$table->boolean('enabled')->comment('Upload enabled');
			$table->integer('company_id')->unsigned()->index('file_uploads_company_id_foreign')->comment('Link to the companies table');
			$table->integer('file_upload_host_id')->unsigned()->index('file_uploads_file_upload_host_id_foreign')->comment('Link to the file upload hosts table');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('file_uploads');
	}

}
