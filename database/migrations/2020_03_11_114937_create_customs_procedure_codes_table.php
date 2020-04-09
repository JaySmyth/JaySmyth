<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomsProcedureCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customs_procedure_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 50);
			$table->string('description', 100);
			$table->string('vat_status', 100);
			$table->char('duty_type', 1);
			$table->boolean('insert_allowed');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customs_procedure_codes');
	}

}
