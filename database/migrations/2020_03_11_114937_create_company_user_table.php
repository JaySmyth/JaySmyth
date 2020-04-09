<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_user', function(Blueprint $table)
		{
			$table->integer('company_id')->unsigned();
			$table->integer('user_id')->unsigned()->index('company_user_user_id_foreign');
			$table->primary(['company_id','user_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_user');
	}

}
