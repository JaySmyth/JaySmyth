<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomsEntryCommoditiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customs_entry_commodities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('vendor', 150)->comment('Vendor');
			$table->string('commodity_code', 20)->comment('Commodity code');
			$table->char('country_of_origin', 2)->nullable();
			$table->decimal('value', 13)->comment('The item value');
			$table->decimal('duty', 13)->comment('The item value');
			$table->decimal('duty_percent', 13)->comment('The item value');
			$table->decimal('vat', 13)->comment('The item value');
			$table->integer('quantity')->unsigned()->comment('The quantity');
			$table->decimal('weight', 13)->comment('The item weight');
			$table->integer('customs_entry_id')->unsigned()->index('customs_entry_commodities_customs_entry_id_foreign')->comment('Link to the customs entries table');
			$table->integer('customs_procedure_code_id')->unsigned()->index('customs_entry_commodities_customs_procedure_code_id_foreign')->comment('Link to the cpcs table');
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
		Schema::drop('customs_entry_commodities');
	}

}
