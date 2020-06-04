<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('packages', function(Blueprint $table)
		{
			$table->foreign('shipment_id')->references('id')->on('shipments')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('packages', function(Blueprint $table)
		{
			$table->dropForeign('packages_shipment_id_foreign');
		});
	}

}
