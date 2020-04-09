<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseInvoiceChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_invoice_charges', function(Blueprint $table)
		{
			$table->foreign('carrier_charge_code_id')->references('id')->on('carrier_charge_codes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('purchase_invoice_charges', function(Blueprint $table)
		{
			$table->dropForeign('purchase_invoice_charges_carrier_charge_code_id_foreign');
			$table->dropForeign('purchase_invoice_charges_purchase_invoice_id_foreign');
		});
	}

}
