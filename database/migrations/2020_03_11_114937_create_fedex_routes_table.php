<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFedexRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fedex_routes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('country_code', 2)->nullable();
			$table->string('zip', 10)->nullable();
			$table->string('zip_from', 10)->nullable();
			$table->string('zip_to', 10)->nullable();
			$table->string('service', 10)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fedex_routes');
	}

}
