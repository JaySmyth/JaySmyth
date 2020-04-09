<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarrierPackagingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carrier_packaging_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10);
			$table->string('rate_code', 10);
			$table->integer('carrier_id')->unsigned()->index('carrier_packaging_types_carrier_id_foreign');
			$table->integer('packaging_type_id')->unsigned()->index('carrier_packaging_types_packaging_type_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('carrier_packaging_types');
	}

}
