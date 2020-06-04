<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentShipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('document_shipment', function(Blueprint $table)
		{
			$table->integer('document_id')->unsigned();
			$table->integer('shipment_id')->unsigned()->index('document_shipment_shipment_id_foreign');
			$table->primary(['document_id','shipment_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('document_shipment');
	}

}
