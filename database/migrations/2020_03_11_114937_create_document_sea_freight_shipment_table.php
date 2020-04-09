<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentSeaFreightShipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('document_sea_freight_shipment', function(Blueprint $table)
		{
			$table->integer('shipment_id')->unsigned();
			$table->integer('document_id')->unsigned()->index('document_sea_freight_shipment_document_id_foreign');
			$table->primary(['shipment_id','document_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('document_sea_freight_shipment');
	}

}
