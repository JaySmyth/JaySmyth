<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDomesticRateDiscountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('domestic_rate_discounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id');
			$table->integer('rate_id');
			$table->string('service');
			$table->string('packaging_code');
			$table->string('area');
			$table->decimal('first_discount', 10, 5);
			$table->decimal('others_discount', 10, 5);
			$table->decimal('notional_discount', 10, 5);
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
			$table->index(['company_id','rate_id','service','packaging_code','area','from_date','to_date'], 'main_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('domestic_rate_discounts');
	}

}
