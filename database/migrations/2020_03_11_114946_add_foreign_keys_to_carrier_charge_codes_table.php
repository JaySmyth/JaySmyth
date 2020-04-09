<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarrierChargeCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('carrier_charge_codes', function(Blueprint $table)
		{
			$table->foreign('carrier_id')->references('id')->on('carriers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('carrier_charge_codes', function(Blueprint $table)
		{
			$table->dropForeign('carrier_charge_codes_carrier_id_foreign');
		});
	}

}
