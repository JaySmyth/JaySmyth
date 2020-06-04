<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('information', 65535);
			$table->text('data', 65535)->nullable();
			$table->text('comments', 65535)->nullable();
			$table->integer('logable_id')->unsigned()->index('logable_id');
			$table->string('logable_type', 60);
			$table->integer('user_id')->unsigned()->index('logs_user_id_foreign');
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
		Schema::drop('logs');
	}

}
