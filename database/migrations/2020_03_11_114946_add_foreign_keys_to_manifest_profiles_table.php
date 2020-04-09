<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToManifestProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('manifest_profiles', function(Blueprint $table)
		{
			$table->foreign('carrier_id')->references('id')->on('carriers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('mode_id')->references('id')->on('modes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('route_id')->references('id')->on('routes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('manifest_profiles', function(Blueprint $table)
		{
			$table->dropForeign('manifest_profiles_carrier_id_foreign');
			$table->dropForeign('manifest_profiles_depot_id_foreign');
			$table->dropForeign('manifest_profiles_mode_id_foreign');
			$table->dropForeign('manifest_profiles_route_id_foreign');
		});
	}

}
