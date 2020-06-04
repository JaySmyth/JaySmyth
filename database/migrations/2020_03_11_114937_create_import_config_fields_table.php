<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImportConfigFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import_config_fields', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50);
			$table->string('description', 50);
			$table->integer('display_order')->unsigned();
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
		Schema::drop('import_config_fields');
	}

}
