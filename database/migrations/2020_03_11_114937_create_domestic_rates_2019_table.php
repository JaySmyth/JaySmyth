<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDomesticRates2019Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('domestic_rates_2019', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('rate_id');
			$table->string('service', 10);
			$table->string('packaging_code', 20);
			$table->decimal('first', 10, 4);
			$table->decimal('others', 10, 4);
			$table->decimal('notional_weight');
			$table->decimal('notional', 10, 4);
			$table->string('area', 3);
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
			$table->index(['rate_id','service','packaging_code','area','from_date','to_date'], 'main_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('domestic_rates_2019');
	}

}
