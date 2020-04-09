<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCountryManifestProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('country_manifest_profile', function(Blueprint $table)
		{
			$table->foreign('country_id')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('manifest_profile_id')->references('id')->on('manifest_profiles')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('country_manifest_profile', function(Blueprint $table)
		{
			$table->dropForeign('country_manifest_profile_country_id_foreign');
			$table->dropForeign('country_manifest_profile_manifest_profile_id_foreign');
		});
	}

}
