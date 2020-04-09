<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompaniesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name', 150);
            $table->string('address1', 150);
            $table->string('address2', 150);
            $table->string('address3', 150);
            $table->string('city', 50);
            $table->string('state', 50);
            $table->string('postcode', 8);
            $table->char('country_code', 3);
            $table->char('address_type', 1)->nullable()->comment('Commercial or residential');
            $table->string('telephone', 15);
            $table->string('email', 150);
            $table->string('site_name', 100)->comment('A name to refer to the company record as - usful if there are multiple sites');
            $table->string('company_code', 6)->unique()->comment('Unique string to identify a company record');
            $table->string('scs_code', 7)->nullable();
            $table->string('eori')->nullable();
            $table->string('group_account', 10)->nullable();
            $table->boolean('bulk_collections')->nullable()->default(0);
            $table->string('carrier_choice', 5)->default('cost')->comment('Basis on how to select carrier to use user, auto, price, cost, dest');
            $table->boolean('vat_exempt');
            $table->boolean('enabled')->comment('Determines if the company is able to ship');
            $table->boolean('upload_only')->nullable()->default(0);
            $table->boolean('testing')->comment('Boolean indicating if the company is in test mode');
            $table->string('notes')->nullable();
            $table->boolean('legacy')->default(0);
            $table->boolean('legacy_pricing')->default(0);
            $table->boolean('legacy_invoice')->default(1);
            $table->integer('print_format_id')->unsigned()->index('companies_print_format_id_foreign')->comment('Link to the print formats table (default site_name size for all users)');
            $table->integer('sale_id')->unsigned()->index('companies_sale_id_foreign')->comment('Link to the sales rep table');
            $table->integer('depot_id')->unsigned()->index('companies_depot_id_foreign')->comment('Link to the depot table');
            $table->string('shipper_type_override', 1)->default('');
            $table->string('recipient_type_override', 1)->default('');
            $table->boolean('master_label')->default(1);
            $table->boolean('commercial_invoice')->default(1);
            $table->boolean('plt_enabled')->default(1);
            $table->boolean('full_dutyandvat')->unsigned()->default(0);
            $table->integer('localisation_id')->unsigned()->index('companies_localisation_id_foreign')->comment('Link to the localisations table');
            $table->integer('pricing_date_offset');
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
        Schema::drop('companies');
    }
}
