<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompanyMessageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_message', function(Blueprint $table)
		{
			$table->foreign('company_id')->references('id')->on('companies')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('message_id')->references('id')->on('messages')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_message', function(Blueprint $table)
		{
			$table->dropForeign('company_message_company_id_foreign');
			$table->dropForeign('company_message_message_id_foreign');
		});
	}

}
