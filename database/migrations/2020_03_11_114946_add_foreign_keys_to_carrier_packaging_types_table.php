<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCarrierPackagingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('carrier_packaging_types', function(Blueprint $table)
		{
			$table->foreign('carrier_id')->references('id')->on('carriers')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('packaging_type_id')->references('id')->on('packaging_types')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('carrier_packaging_types', function(Blueprint $table)
		{
			$table->dropForeign('carrier_packaging_types_carrier_id_foreign');
			$table->dropForeign('carrier_packaging_types_packaging_type_id_foreign');
		});
	}

}
