<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMessageUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('message_user', function(Blueprint $table)
		{
			$table->foreign('message_id')->references('id')->on('messages')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('message_user', function(Blueprint $table)
		{
			$table->dropForeign('message_user_message_id_foreign');
			$table->dropForeign('message_user_user_id_foreign');
		});
	}

}
