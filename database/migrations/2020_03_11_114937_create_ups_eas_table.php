<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUpsEasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ups_eas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('country_name');
			$table->string('recipient_country_code', 2);
			$table->string('from_recipient_postcode', 10);
			$table->string('to_recipient_postcode', 10);
			$table->string('recipient_city', 50);
			$table->string('origin_surcharge', 50);
			$table->string('destination_surcharge', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ups_eas');
	}

}
