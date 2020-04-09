<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRateChangeLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rate_change_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->comment('User id of user making change');
			$table->integer('company_id')->unsigned()->comment('Company id of rate being changed');
			$table->integer('service_id')->unsigned()->comment('Service id of rate being changed');
			$table->integer('rate_id')->unsigned()->comment('Rate id of new rate');
			$table->string('directory', 100)->comment('Directory that the file will be uploaded to');
			$table->string('filename', 100)->comment('Name of uploaded file');
			$table->string('action')->nullable()->comment('Brief description of action');
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
		Schema::drop('rate_change_logs');
	}

}
