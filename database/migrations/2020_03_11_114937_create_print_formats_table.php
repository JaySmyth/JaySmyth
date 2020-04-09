<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrintFormatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('print_formats', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10)->unique();
			$table->string('size', 50);
			$table->string('format', 50);
			$table->string('name', 50);
			$table->decimal('width', 5)->comment('Width in mm');
			$table->decimal('height', 5)->comment('Height in mm');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('print_formats');
	}

}
