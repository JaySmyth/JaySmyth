<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_message', function(Blueprint $table)
		{
			$table->integer('company_id')->unsigned();
			$table->integer('message_id')->unsigned()->index('company_message_message_id_foreign');
			$table->primary(['company_id','message_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_message');
	}

}
