<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSequencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sequences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 16)->unique();
			$table->integer('start_number')->unsigned();
			$table->integer('finish_number')->unsigned();
			$table->integer('next_available')->unsigned();
			$table->string('comment', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sequences');
	}

}
