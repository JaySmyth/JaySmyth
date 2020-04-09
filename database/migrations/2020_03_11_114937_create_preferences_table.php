<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('preferences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('field', 50);
			$table->string('value');
			$table->integer('user_id')->unsigned()->index('preferences_user_id_foreign');
			$table->integer('company_id')->unsigned()->index('preferences_company_id_foreign');
			$table->integer('mode_id')->unsigned()->index('preferences_mode_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('preferences');
	}

}
