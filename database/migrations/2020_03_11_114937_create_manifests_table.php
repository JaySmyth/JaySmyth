<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateManifestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('manifests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('number', 20);
			$table->boolean('uploaded');
			$table->integer('mode_id')->unsigned()->index('manifests_mode_id_foreign');
			$table->integer('carrier_id')->unsigned()->index('manifests_carrier_id_foreign');
			$table->integer('depot_id')->unsigned()->index('manifests_depot_id_foreign');
			$table->integer('manifest_profile_id')->unsigned()->index('manifests_manifest_profile_id_foreign');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('manifests');
	}

}
