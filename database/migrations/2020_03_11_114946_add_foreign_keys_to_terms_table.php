<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('terms', function(Blueprint $table)
		{
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
		Schema::table('terms', function(Blueprint $table)
		{
			$table->dropForeign('terms_mode_id_foreign');
		});
	}

}
