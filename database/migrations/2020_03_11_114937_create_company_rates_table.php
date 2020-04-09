<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyRatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_rates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index();
			$table->integer('rate_id')->unsigned();
			$table->decimal('discount');
			$table->boolean('special_discount')->default(0);
			$table->string('fuel_cap')->default('99.99');
			$table->integer('service_id')->unsigned();
			$table->timestamps();
			$table->index(['company_id','rate_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_rates');
	}

}
