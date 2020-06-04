<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRateDiscountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rate_discounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id');
			$table->integer('rate_id');
			$table->integer('service_id');
			$table->boolean('residential');
			$table->integer('piece_limit');
			$table->string('package_type');
			$table->string('zone');
			$table->decimal('break_point');
			$table->decimal('weight_discount', 10, 5);
			$table->decimal('package_discount', 10, 5);
			$table->decimal('consignment_discount', 10, 5);
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
			$table->index(['company_id','rate_id','residential','piece_limit','package_type','zone','break_point','from_date','to_date'], 'main_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rate_discounts');
	}

}
