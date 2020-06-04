<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSeaFreightShipmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sea_freight_shipments', function(Blueprint $table)
		{
			$table->foreign('company_id')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sea_freight_status_id')->references('id')->on('sea_freight_statuses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('shipping_line_id')->references('id')->on('shipping_lines')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('sea_freight_shipments', function(Blueprint $table)
		{
			$table->dropForeign('sea_freight_shipments_company_id_foreign');
			$table->dropForeign('sea_freight_shipments_depot_id_foreign');
			$table->dropForeign('sea_freight_shipments_sea_freight_status_id_foreign');
			$table->dropForeign('sea_freight_shipments_shipping_line_id_foreign');
			$table->dropForeign('sea_freight_shipments_user_id_foreign');
		});
	}

}
