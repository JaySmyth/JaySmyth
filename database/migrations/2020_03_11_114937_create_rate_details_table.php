<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRateDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rate_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('rate_id');
			$table->boolean('residential');
			$table->integer('piece_limit');
			$table->string('package_type', 20);
			$table->string('zone', 3);
			$table->decimal('break_point');
			$table->decimal('weight_rate', 10, 4);
			$table->integer('weight_increment')->default(1);
			$table->decimal('package_rate', 10, 4);
			$table->decimal('consignment_rate', 10, 4);
			$table->string('weight_units', 2);
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
			$table->index(['rate_id','residential','piece_limit','package_type','zone','break_point','from_date','to_date'], 'main_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rate_details');
	}

}
