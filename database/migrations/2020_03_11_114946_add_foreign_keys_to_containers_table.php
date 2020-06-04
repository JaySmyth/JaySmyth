<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToContainersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('containers', function(Blueprint $table)
		{
			$table->foreign('sea_freight_shipment_id')->references('id')->on('sea_freight_shipments')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('containers', function(Blueprint $table)
		{
			$table->dropForeign('containers_sea_freight_shipment_id_foreign');
		});
	}

}
