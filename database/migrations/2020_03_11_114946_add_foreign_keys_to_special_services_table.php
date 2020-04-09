<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSpecialServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('special_services', function(Blueprint $table)
		{
			$table->foreign('carrier_id')->references('id')->on('carriers')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('service_id')->references('id')->on('services')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('special_services', function(Blueprint $table)
		{
			$table->dropForeign('special_services_carrier_id_foreign');
			$table->dropForeign('special_services_service_id_foreign');
		});
	}

}
