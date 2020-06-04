<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountryManifestProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('country_manifest_profile', function(Blueprint $table)
		{
			$table->integer('country_id')->unsigned()->index('country_manifest_profile_country_id_foreign');
			$table->integer('manifest_profile_id')->unsigned();
			$table->primary(['manifest_profile_id','country_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('country_manifest_profile');
	}

}
