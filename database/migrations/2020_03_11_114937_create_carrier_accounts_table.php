<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarrierAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carrier_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('carrier_id')->unsigned();
			$table->string('account', 14);
			$table->string('company_name', 150);
			$table->string('address1', 150);
			$table->string('address2', 150);
			$table->string('address3', 150);
			$table->string('city', 50);
			$table->string('state', 50);
			$table->string('postcode', 8);
			$table->char('country_code', 3);
			$table->string('telephone', 15);
			$table->string('vat_number', 15);
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
		Schema::drop('carrier_accounts');
	}

}
