<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseInvoiceChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_invoice_charges', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10)->nullable();
			$table->string('description', 100)->nullable();
			$table->decimal('amount', 13)->nullable();
			$table->char('currency_code', 3)->nullable();
			$table->decimal('exchange_rate', 13)->nullable();
			$table->decimal('billed_amount', 13)->nullable();
			$table->char('billed_amount_currency_code', 3)->nullable();
			$table->boolean('vat_applied')->nullable();
			$table->decimal('vat', 13)->nullable();
			$table->decimal('vat_rate', 13)->nullable();
			$table->integer('carrier_charge_code_id')->unsigned()->nullable()->index('purchase_invoice_charges_carrier_charge_code_id_foreign');
			$table->integer('purchase_invoice_id')->unsigned()->index('purchase_invoice_charges_purchase_invoice_id_foreign');
			$table->integer('purchase_invoice_line_id')->unsigned();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('purchase_invoice_charges');
	}

}
