<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIfsNdPostcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ifs_nd_postcodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('postcode', 10)->unique('ni_ooa_postcode_unique');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ifs_nd_postcodes');
	}

}
