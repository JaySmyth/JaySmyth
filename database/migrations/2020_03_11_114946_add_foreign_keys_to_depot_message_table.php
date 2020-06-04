<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDepotMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('depot_message', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('message_id')->references('id')->on('messages')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('depot_message', function(Blueprint $table)
		{
			$table->dropForeign('depot_message_depot_id_foreign');
			$table->dropForeign('depot_message_message_id_foreign');
		});
	}

}
