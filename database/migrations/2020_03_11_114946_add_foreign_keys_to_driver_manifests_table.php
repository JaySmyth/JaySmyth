<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDriverManifestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('driver_manifests', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('driver_id')->references('id')->on('drivers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('vehicle_id')->references('id')->on('vehicles')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('driver_manifests', function(Blueprint $table)
		{
			$table->dropForeign('driver_manifests_depot_id_foreign');
			$table->dropForeign('driver_manifests_driver_id_foreign');
			$table->dropForeign('driver_manifests_vehicle_id_foreign');
		});
	}

}
