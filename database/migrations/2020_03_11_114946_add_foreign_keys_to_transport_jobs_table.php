<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTransportJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transport_jobs', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('driver_manifest_id')->references('id')->on('driver_manifests')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('shipment_id')->references('id')->on('shipments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id')->references('id')->on('statuses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transport_jobs', function(Blueprint $table)
		{
			$table->dropForeign('transport_jobs_depot_id_foreign');
			$table->dropForeign('transport_jobs_driver_manifest_id_foreign');
			$table->dropForeign('transport_jobs_shipment_id_foreign');
			$table->dropForeign('transport_jobs_status_id_foreign');
		});
	}

}
