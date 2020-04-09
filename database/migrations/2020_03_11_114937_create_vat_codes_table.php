<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVatCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vat_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('code');
			$table->decimal('percent');
			$table->date('from_date');
			$table->date('to_date');
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
		Schema::drop('vat_codes');
	}

}
