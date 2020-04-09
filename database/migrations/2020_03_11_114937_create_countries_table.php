<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('country', 100)->index();
			$table->char('country_code', 2)->index();
			$table->char('alpha', 3);
			$table->integer('iso')->unsigned();
			$table->boolean('eu')->index();
			$table->string('postal_validation', 200);
			$table->string('postcode_example', 8);
			$table->char('currency_code', 3);
			$table->integer('fedex_route');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}
