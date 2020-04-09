<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50)->nullable();
			$table->string('company_name')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('address3')->nullable();
			$table->string('city', 50)->nullable();
			$table->string('state', 50)->nullable();
			$table->string('postcode', 8)->nullable();
			$table->string('country_code', 2)->nullable();
			$table->string('telephone', 15)->nullable();
			$table->string('email', 100)->nullable();
			$table->char('type', 1)->nullable();
			$table->string('definition', 10)->index('addresses_definition');
			$table->string('account_number', 15)->nullable();
			$table->integer('company_id')->unsigned()->index('addresses_company_id_foreign');
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
		Schema::drop('addresses');
	}

}
