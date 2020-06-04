<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarrierChargeCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carrier_charge_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 5);
			$table->string('description', 100);
			$table->char('scs_code', 3);
			$table->integer('carrier_id')->unsigned()->index('carrier_charge_codes_carrier_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('carrier_charge_codes');
	}

}
