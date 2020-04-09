<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseInvoicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number', 20);
            $table->string('account_number', 15);
            $table->decimal('total', 13);
            $table->decimal('total_taxable', 13);
            $table->decimal('total_non_taxable', 13);
            $table->decimal('vat', 13);
            $table->char('currency_code', 3);
            $table->char('type', 1);
            $table->char('import_export', 1)->nullable();
            $table->boolean('exported');
            $table->boolean('received');
            $table->boolean('queried');
            $table->boolean('costs');
            $table->boolean('copy_docs');
            $table->boolean('copy_docs_email_sent');
            $table->boolean('xml_generated');
            $table->integer('status')->unsigned();
            $table->string('error')->nullable();
            $table->integer('carrier_id')->unsigned()->index('purchase_invoices_carrier_id_foreign');
            $table->timestamp('date')->nullable();
            $table->timestamp('date_received')->nullable();
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
        Schema::drop('purchase_invoices');
    }
}
