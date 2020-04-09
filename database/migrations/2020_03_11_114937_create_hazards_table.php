<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHazardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hazards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 5);
			$table->string('description', 50);
			$table->integer('mode_id')->unsigned()->index('hazards_mode_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hazards');
	}

}
