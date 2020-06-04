<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('routes', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('routes', function(Blueprint $table)
		{
			$table->dropForeign('routes_depot_id_foreign');
		});
	}

}
