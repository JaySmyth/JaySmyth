<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceRunsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_runs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('department_id');
			$table->integer('user_id');
			$table->string('status', 10)->default('Failed');
			$table->timestamp('last_run')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_runs');
	}

}
