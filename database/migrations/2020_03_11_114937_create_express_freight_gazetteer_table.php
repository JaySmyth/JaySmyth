<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpressFreightGazetteerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('express_freight_gazetteer', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('postcode', 10);
			$table->string('bay', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('express_freight_gazetteer');
	}

}
