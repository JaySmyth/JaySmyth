<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommoditiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commodities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('description')->nullable();
			$table->string('product_code', 50)->nullable();
			$table->char('country_of_manufacture', 2);
			$table->string('manufacturer', 100)->nullable();
			$table->decimal('unit_value', 13);
			$table->char('currency_code', 3);
			$table->decimal('unit_weight', 13);
			$table->string('weight_uom', 5);
			$table->string('uom', 5);
			$table->string('commodity_code', 15)->nullable();
			$table->string('harmonized_code', 15)->nullable();
			$table->decimal('shipping_cost', 13);
			$table->integer('company_id')->unsigned()->index('commodities_company_id_foreign');
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
		Schema::drop('commodities');
	}

}
