<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToQuotationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quotations', function(Blueprint $table)
		{
			$table->foreign('department_id')->references('id')->on('departments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('sale_id')->references('id')->on('sales')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quotations', function(Blueprint $table)
		{
			$table->dropForeign('quotations_department_id_foreign');
			$table->dropForeign('quotations_sale_id_foreign');
			$table->dropForeign('quotations_user_id_foreign');
		});
	}

}
