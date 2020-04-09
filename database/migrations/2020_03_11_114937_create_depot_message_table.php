<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepotMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('depot_message', function(Blueprint $table)
		{
			$table->integer('depot_id')->unsigned();
			$table->integer('message_id')->unsigned()->index('depot_message_message_id_foreign');
			$table->primary(['depot_id','message_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('depot_message');
	}

}
