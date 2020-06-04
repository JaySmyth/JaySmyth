<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDespatchNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('despatch_notes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('returns', 65535);
			$table->integer('company_id')->unsigned()->index('despatch_notes_company_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('despatch_notes');
	}

}
