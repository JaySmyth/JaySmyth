<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDriversTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('drivers', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('drivers', function(Blueprint $table)
		{
			$table->dropForeign('drivers_depot_id_foreign');
			$table->dropForeign('drivers_vehicle_id_foreign');
		});
	}

}
