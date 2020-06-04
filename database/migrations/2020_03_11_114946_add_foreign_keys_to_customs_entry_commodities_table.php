<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCustomsEntryCommoditiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customs_entry_commodities', function(Blueprint $table)
		{
			$table->foreign('customs_entry_id')->references('id')->on('customs_entries')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('customs_procedure_code_id')->references('id')->on('customs_procedure_codes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customs_entry_commodities', function(Blueprint $table)
		{
			$table->dropForeign('customs_entry_commodities_customs_entry_id_foreign');
			$table->dropForeign('customs_entry_commodities_customs_procedure_code_id_foreign');
		});
	}

}
