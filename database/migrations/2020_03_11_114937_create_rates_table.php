<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('rate_type', 1);
			$table->string('description', 100);
			$table->char('currency_code', 3);
			$table->char('weight_units', 3);
			$table->integer('volumetric_divisor');
			$table->decimal('residential_charge', 10);
			$table->string('model', 10);
			$table->integer('surcharge_id')->default(0);
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
		Schema::drop('rates');
	}

}
