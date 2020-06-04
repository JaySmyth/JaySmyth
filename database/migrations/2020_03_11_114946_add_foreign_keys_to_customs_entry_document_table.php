<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCustomsEntryDocumentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customs_entry_document', function(Blueprint $table)
		{
			$table->foreign('customs_entry_id')->references('id')->on('customs_entries')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('document_id')->references('id')->on('documents')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customs_entry_document', function(Blueprint $table)
		{
			$table->dropForeign('customs_entry_document_customs_entry_id_foreign');
			$table->dropForeign('customs_entry_document_document_id_foreign');
		});
	}

}
