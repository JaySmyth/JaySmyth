<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('filename', 100)->comment('Original file name');
			$table->string('document_type', 50)->nullable()->comment('Doc type e.g Commercial Invoice');
			$table->string('description', 80)->comment('Document description provided by the user');
			$table->string('path')->comment('Path to the file on S3');
			$table->string('type', 30)->comment('File mime type');
			$table->integer('size')->unsigned()->comment('File size in bytes');
			$table->string('public_url')->comment('Publicly accessible URL - S3 link');
			$table->integer('user_id')->unsigned()->index('documents_user_id_foreign')->comment('Link to the users table');
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
		Schema::drop('documents');
	}

}
