<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRfSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rf_sessions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('session_id', 30);
			$table->integer('user_id')->nullable();
			$table->string('user_name')->nullable();
			$table->string('route', 10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rf_sessions');
	}

}
