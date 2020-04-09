<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToShipmentContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shipment_contents', function(Blueprint $table)
		{
			$table->foreign('shipment_id')->references('id')->on('shipments')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shipment_contents', function(Blueprint $table)
		{
			$table->dropForeign('shipment_contents_shipment_id_foreign');
		});
	}

}
