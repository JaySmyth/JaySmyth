<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeaFreightStatusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sea_freight_statuses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 50);
			$table->string('name', 50);
			$table->string('description', 100);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sea_freight_statuses');
	}

}
