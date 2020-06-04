<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompanyPackagingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_packaging_types', function(Blueprint $table)
		{
			$table->foreign('mode_id')->references('id')->on('modes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('packaging_type_id')->references('id')->on('packaging_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_packaging_types', function(Blueprint $table)
		{
			$table->dropForeign('company_packaging_types_mode_id_foreign');
			$table->dropForeign('company_packaging_types_packaging_type_id_foreign');
		});
	}

}
