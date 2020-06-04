<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommoditiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('commodities', function(Blueprint $table)
		{
			$table->foreign('company_id')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('commodities', function(Blueprint $table)
		{
			$table->dropForeign('commodities_company_id_foreign');
		});
	}

}
