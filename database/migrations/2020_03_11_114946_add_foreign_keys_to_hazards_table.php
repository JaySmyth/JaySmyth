<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHazardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hazards', function(Blueprint $table)
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
		Schema::table('hazards', function(Blueprint $table)
		{
			$table->dropForeign('hazards_mode_id_foreign');
		});
	}

}
