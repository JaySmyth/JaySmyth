<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCollectionSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('collection_settings', function(Blueprint $table)
		{
			$table->foreign('company_id')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('collection_settings', function(Blueprint $table)
		{
			$table->dropForeign('collection_settings_company_id_foreign');
		});
	}

}
