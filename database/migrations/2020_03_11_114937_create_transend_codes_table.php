<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransendCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transend_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('code', 15);
			$table->string('description', 100);
			$table->boolean('resend')->default(0);
			$table->boolean('resend_same_day')->default(0);
			$table->boolean('hold')->default(0);
			$table->boolean('no_collection')->default(0);
			$table->boolean('add_tracking_event')->default(0);
			$table->boolean('notify_department')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transend_codes');
	}

}
