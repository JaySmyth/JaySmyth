<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCollectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('collections', function(Blueprint $table)
		{
			$table->foreign('company_id')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('depot_id')->references('id')->on('depots')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id')->references('id')->on('statuses')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('collections', function(Blueprint $table)
		{
			$table->dropForeign('collections_company_id_foreign');
			$table->dropForeign('collections_depot_id_foreign');
			$table->dropForeign('collections_status_id_foreign');
			$table->dropForeign('collections_user_id_foreign');
		});
	}

}
