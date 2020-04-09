<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomsEntryDocumentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customs_entry_document', function(Blueprint $table)
		{
			$table->integer('customs_entry_id')->unsigned();
			$table->integer('document_id')->unsigned()->index('customs_entry_document_document_id_foreign');
			$table->primary(['customs_entry_id','document_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customs_entry_document');
	}

}
