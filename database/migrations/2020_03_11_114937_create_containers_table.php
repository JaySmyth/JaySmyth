<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContainersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('containers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('size', 50);
			$table->string('number', 50)->nullable();
			$table->string('seal_number', 50)->nullable();
			$table->string('goods_description', 100)->nullable();
			$table->integer('number_of_cartons')->unsigned();
			$table->decimal('weight', 13)->nullable();
			$table->string('additional_information')->nullable();
			$table->integer('sea_freight_shipment_id')->unsigned()->index('containers_sea_freight_shipment_id_foreign')->comment('Link to the shipments table');
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
		Schema::drop('containers');
	}

}
