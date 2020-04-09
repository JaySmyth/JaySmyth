<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipment_contents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('package_index')->unsigned();
			$table->string('description')->nullable();
			$table->string('manufacturer', 100)->nullable();
			$table->string('product_code', 50)->nullable();
			$table->string('commodity_code', 15)->nullable();
			$table->string('harmonized_code', 15)->nullable();
			$table->char('country_of_manufacture', 2)->nullable();
			$table->integer('quantity')->unsigned()->nullable();
			$table->string('uom', 3)->nullable()->comment('Quantity UOM');
			$table->decimal('unit_value', 13)->nullable();
			$table->char('currency_code', 3)->nullable();
			$table->decimal('unit_weight', 13)->nullable();
			$table->string('weight_uom', 5)->nullable();
			$table->integer('shipment_id')->unsigned()->index('shipment_contents_shipment_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shipment_contents');
	}

}
