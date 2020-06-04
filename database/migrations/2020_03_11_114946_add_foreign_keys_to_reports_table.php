<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reports', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('reports', function(Blueprint $table)
		{
			$table->dropForeign('reports_depot_id_foreign');
			$table->dropForeign('reports_mode_id_foreign');
		});
	}

}
