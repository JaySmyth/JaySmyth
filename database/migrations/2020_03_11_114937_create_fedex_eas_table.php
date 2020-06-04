<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFedexEasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fedex_eas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('postcode', 10);
			$table->string('locn', 50);
			$table->string('type', 3);
			$table->decimal('cost_consignment_rate', 10)->nullable();
			$table->decimal('cost_weight_rate', 10)->nullable();
			$table->decimal('sales_consignment_rate', 10)->nullable();
			$table->decimal('sales_weight_rate', 10)->nullable();
			$table->string('currency', 10)->nullable();
			$table->date('from_date')->nullable();
			$table->date('to_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fedex_eas');
	}

}
