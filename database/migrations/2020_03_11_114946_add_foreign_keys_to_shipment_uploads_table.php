<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToShipmentUploadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shipment_uploads', function(Blueprint $table)
		{
			$table->foreign('import_config_id')->references('id')->on('import_configs')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shipment_uploads', function(Blueprint $table)
		{
			$table->dropForeign('shipment_uploads_import_config_id_foreign');
		});
	}

}
