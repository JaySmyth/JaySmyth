<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('postcodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('country_code', 2)->default('gb');
			$table->string('postcode', 12)->unique();
			$table->time('pickup_time');
			$table->string('collection_route', 20);
			$table->string('delivery_route', 20);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('postcodes');
	}

}
