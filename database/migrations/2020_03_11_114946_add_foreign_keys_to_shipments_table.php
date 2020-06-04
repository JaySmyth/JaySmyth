<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToShipmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shipments', function(Blueprint $table)
		{
			$table->foreign('carrier_id')->references('id')->on('carriers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('company_id')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('department_id')->references('id')->on('departments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('manifest_id')->references('id')->on('manifests')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('mode_id')->references('id')->on('modes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('route_id')->references('id')->on('routes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('service_id')->references('id')->on('services')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id')->references('id')->on('statuses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shipments', function(Blueprint $table)
		{
			$table->dropForeign('shipments_carrier_id_foreign');
			$table->dropForeign('shipments_company_id_foreign');
			$table->dropForeign('shipments_department_id_foreign');
			$table->dropForeign('shipments_depot_id_foreign');
			$table->dropForeign('shipments_manifest_id_foreign');
			$table->dropForeign('shipments_mode_id_foreign');
			$table->dropForeign('shipments_route_id_foreign');
			$table->dropForeign('shipments_service_id_foreign');
			$table->dropForeign('shipments_status_id_foreign');
			$table->dropForeign('shipments_user_id_foreign');
		});
	}

}
