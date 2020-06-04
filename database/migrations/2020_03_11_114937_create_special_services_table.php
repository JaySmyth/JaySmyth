<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpecialServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('special_services', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10);
			$table->string('name', 50);
			$table->integer('carrier_id')->unsigned()->index('special_services_carrier_id_foreign');
			$table->integer('service_id')->unsigned()->index('special_services_service_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('special_services');
	}

}
