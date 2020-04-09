<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseInvoiceLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_invoice_lines', function(Blueprint $table)
		{
			$table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoices')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_invoice_lines', function(Blueprint $table)
		{
			$table->dropForeign('purchase_invoice_lines_purchase_invoice_id_foreign');
		});
	}

}
