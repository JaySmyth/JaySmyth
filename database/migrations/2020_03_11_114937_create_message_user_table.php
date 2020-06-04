<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessageUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('message_user', function(Blueprint $table)
		{
			$table->integer('message_id')->unsigned();
			$table->integer('user_id')->unsigned()->index('message_user_user_id_foreign');
			$table->primary(['message_id','user_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('message_user');
	}

}
