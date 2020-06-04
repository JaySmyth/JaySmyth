<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProblemEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('problem_events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('event')->comment('A problem tracking event to monitor for');
			$table->string('relevance', 20)->comment('Who the event is relevant to (s)sender or (r)ecipient');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('problem_events');
	}

}
