<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDocumentSeaFreightShipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('document_sea_freight_shipment', function(Blueprint $table)
		{
			$table->foreign('document_id')->references('id')->on('documents')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('shipment_id')->references('id')->on('sea_freight_shipments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('document_sea_freight_shipment', function(Blueprint $table)
		{
			$table->dropForeign('document_sea_freight_shipment_document_id_foreign');
			$table->dropForeign('document_sea_freight_shipment_shipment_id_foreign');
		});
	}

}
