<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToManifestProfileServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('manifest_profile_service', function(Blueprint $table)
		{
			$table->foreign('manifest_profile_id')->references('id')->on('manifest_profiles')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('manifest_profile_service', function(Blueprint $table)
		{
			$table->dropForeign('manifest_profile_service_manifest_profile_id_foreign');
			$table->dropForeign('manifest_profile_service_service_id_foreign');
		});
	}

}
