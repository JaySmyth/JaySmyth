<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTntPostcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tnt_postcodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('country_code', 2);
			$table->string('country', 50);
			$table->string('postcode_from', 12);
			$table->string('postcode_to', 12);
			$table->string('town', 100);
			$table->string('province', 100);
			$table->char('destination_station', 3)->nullable();
			$table->string('depotname', 100)->nullable();
			$table->string('controlling_station', 100)->nullable();
			$table->char('zone', 1)->nullable();
			$table->string('satellite_code', 10)->nullable();
			$table->char('delivery_Party', 1)->nullable();
			$table->char('saturday', 1)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tnt_postcodes');
	}

}
