<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateManifestProfileServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('manifest_profile_service', function(Blueprint $table)
		{
			$table->integer('manifest_profile_id')->unsigned();
			$table->integer('service_id')->unsigned()->index('manifest_profile_service_service_id_foreign');
			$table->primary(['manifest_profile_id','service_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('manifest_profile_service');
	}

}
