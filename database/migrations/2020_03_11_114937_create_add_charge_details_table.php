<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddChargeDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('add_charge_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50);
			$table->string('code', 10);
			$table->decimal('weight_rate', 10)->nullable();
			$table->decimal('package_rate', 10)->nullable();
			$table->decimal('consignment_rate', 10)->nullable();
			$table->decimal('min', 10)->nullable();
			$table->integer('company_id');
			$table->date('from_date');
			$table->date('to_date');
			$table->integer('add_charge_id');
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
		Schema::drop('add_charge_details');
	}

}
