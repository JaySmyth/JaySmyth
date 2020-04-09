<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToManifestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('manifests', function(Blueprint $table)
		{
			$table->foreign('carrier_id')->references('id')->on('carriers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('manifest_profile_id')->references('id')->on('manifest_profiles')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('mode_id')->references('id')->on('modes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('manifests', function(Blueprint $table)
		{
			$table->dropForeign('manifests_carrier_id_foreign');
			$table->dropForeign('manifests_depot_id_foreign');
			$table->dropForeign('manifests_manifest_profile_id_foreign');
			$table->dropForeign('manifests_mode_id_foreign');
		});
	}

}
