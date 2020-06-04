<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('states', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code')->unique()->comment('2 char ansi code');
			$table->string('alpha_code')->unique()->comment('State name with spaces and punctuation removed');
			$table->string('name')->comment('State name');
			$table->string('country_code');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('states');
	}

}
