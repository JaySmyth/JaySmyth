<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChargeCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('charge_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10);
			$table->string('description', 50);
			$table->string('scs_code', 10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('charge_codes');
	}

}
