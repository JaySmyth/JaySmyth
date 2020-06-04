<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDocumentShipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('document_shipment', function(Blueprint $table)
		{
			$table->foreign('document_id')->references('id')->on('documents')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('shipment_id')->references('id')->on('shipments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('document_shipment', function(Blueprint $table)
		{
			$table->dropForeign('document_shipment_document_id_foreign');
			$table->dropForeign('document_shipment_shipment_id_foreign');
		});
	}

}
