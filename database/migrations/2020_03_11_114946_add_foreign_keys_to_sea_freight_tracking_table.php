<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSeaFreightTrackingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sea_freight_tracking', function(Blueprint $table)
		{
			$table->foreign('sea_freight_shipment_id')->references('id')->on('sea_freight_shipments')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sea_freight_tracking', function(Blueprint $table)
		{
			$table->dropForeign('sea_freight_tracking_sea_freight_shipment_id_foreign');
		});
	}

}
