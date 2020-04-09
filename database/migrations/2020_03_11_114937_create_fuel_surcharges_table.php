<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFuelSurchargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fuel_surcharges', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('carrier_id');
			$table->string('service_code', 10);
			$table->decimal('fuel_percent', 10, 4);
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fuel_surcharges');
	}

}
