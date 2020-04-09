<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDhlEasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dhl_eas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('recipient_country_code', 2);
			$table->string('iata_code', 3);
			$table->string('recipient_state', 50)->nullable();
			$table->string('recipient_town', 50)->nullable();
			$table->string('recipient_postcode', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dhl_eas');
	}

}
