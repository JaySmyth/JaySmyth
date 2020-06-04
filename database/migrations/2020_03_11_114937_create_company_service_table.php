<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_service', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50)->comment('Customers custom name for the service');
			$table->integer('preference')->unsigned()->comment('Order of preference');
			$table->string('account', 30)->comment('Account no to use with carrier');
			$table->string('scs_account', 7);
			$table->string('country_filter', 100);
			$table->integer('monthly_limit')->unsigned()->nullable();
			$table->integer('max_weight_limit')->unsigned()->nullable();
			$table->integer('company_id')->unsigned()->index('company_service_company_id_foreign')->comment('Link to the companies table');
			$table->integer('service_id')->unsigned()->index('company_service_service_id_foreign')->comment('Link to the services table');
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
		Schema::drop('company_service');
	}

}
