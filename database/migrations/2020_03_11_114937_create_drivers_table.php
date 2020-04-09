<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriversTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('drivers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 30);
			$table->boolean('enabled');
			$table->string('telephone', 15);
			$table->integer('vehicle_id')->unsigned()->nullable()->index('drivers_vehicle_id_foreign')->comment('Drivers default vehicle');
			$table->integer('depot_id')->unsigned()->index('drivers_depot_id_foreign')->comment('Link to the depots table');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('drivers');
	}

}
