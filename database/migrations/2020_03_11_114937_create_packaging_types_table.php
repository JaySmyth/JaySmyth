<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packaging_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10)->unique()->comment('A unique packaging code used by IFS');
			$table->string('name', 50);
			$table->integer('mode_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('packaging_types');
	}

}
