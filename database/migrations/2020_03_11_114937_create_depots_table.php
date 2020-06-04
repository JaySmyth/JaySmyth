<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('depots', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('code', 3);
			$table->string('name', 50);
			$table->string('address1', 150);
			$table->string('address2', 150);
			$table->string('address3', 150);
			$table->string('city', 50);
			$table->string('state', 50);
			$table->string('postcode', 8);
			$table->char('country_code', 3);
			$table->string('email', 100);
			$table->string('telephone', 15);
			$table->integer('localisation_id')->unsigned()->comment('Link to the localisations table');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('depots');
	}

}
