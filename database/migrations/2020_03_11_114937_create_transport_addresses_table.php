<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransportAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transport_addresses', function(Blueprint $table)
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
		Schema::drop('transport_addresses');
	}

}
