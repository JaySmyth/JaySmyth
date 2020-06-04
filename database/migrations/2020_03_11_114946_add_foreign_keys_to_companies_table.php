<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function(Blueprint $table)
		{
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('localisation_id')->references('id')->on('localisations')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('print_format_id')->references('id')->on('print_formats')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sale_id')->references('id')->on('sales')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('companies', function(Blueprint $table)
		{
			$table->dropForeign('companies_depot_id_foreign');
			$table->dropForeign('companies_localisation_id_foreign');
			$table->dropForeign('companies_print_format_id_foreign');
			$table->dropForeign('companies_sale_id_foreign');
		});
	}

}
