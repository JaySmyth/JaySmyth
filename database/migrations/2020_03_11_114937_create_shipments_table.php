<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('consignment_number', 12)->index()->comment('IFS generated consignment number for the shipment');
            $table->string('carrier_consignment_number', 30)->nullable()->index()->comment('The code the carrier refers to this consignment (chosen by the carrier)');
            $table->string('carrier_tracking_number', 30)->nullable()->index()->comment('Tracking number that the carrier may use for this consignment');
            $table->string('shipment_reference', 30)->nullable()->index()->comment('Senders reference for the shipment');
            $table->string('order_number')->nullable();
            $table->string('token', 15)->nullable()->index()->comment('A unique token to be passed in tracking URL or other public links');
            $table->string('source', 32)->nullable()->comment('File upload / import identifier');
            $table->integer('pieces')->unsigned()->comment('The number of packages (pieces) in the consignment.');
            $table->decimal('weight', 13)->nullable()->comment('The weight of the consignment in kg');
            $table->string('weight_uom', 3)->nullable()->comment('Default is kg (US based customers may override with lbs)');
            $table->decimal('supplied_weight', 13)->nullable();
            $table->string('dims_uom', 4)->nullable()->comment('Default is cm (US based customers may override with inch)');
            $table->decimal('volumetric_weight', 13)->nullable()->comment('The volumetric weight of the consignment');
            $table->integer('volumetric_divisor')->unsigned()->nullable()->comment('Value used to calculate the volumetric weight e.g. 6000');
            $table->decimal('supplied_volumetric_weight', 13)->nullable()->comment('Customer supplied volumetric weight');
            $table->decimal('customs_value', 13)->nullable()->comment('The value of the shipment');
            $table->char('customs_value_currency_code', 3)->nullable()->comment('The currency to be used for  the consignment value. For example GBP, USD');
            $table->string('documents_description', 50)->nullable()->comment('Description of documents if docs only shipment');
            $table->string('goods_description', 50)->nullable()->comment('Description of goods if domestic shipment');
            $table->string('special_instructions', 100)->nullable()->comment('Special instructions for the driver');
            $table->decimal('max_dimension', 13)->comment('The length of the longest side (in CM).');
            $table->boolean('on_hold')->nullable()->default(0)->comment('Flag indicating shipment has been held by IFS');
            $table->boolean('received')->default(0)->comment('Flag indicating that all packages have been received by IFS');
            $table->boolean('created_sent')->default(0);
            $table->boolean('received_sent')->default(0);
            $table->boolean('pallet')->default(0)->comment('Flag indicating if the consignment contains one or more pallets');
            $table->boolean('delivered')->default(0)->comment('Flag indicating if the shipment has been delivered');
            $table->string('pod_signature', 50)->nullable()->comment('POD signature');
            $table->string('pod_image')->nullable();
            $table->boolean('pod_sent')->default(0);
            $table->string('scs_job_number', 15)->nullable()->index()->comment('SCS job number to link in with SCS');
            $table->integer('invoicing_status')->unsigned()->default(0)->comment('Invoice status indicator');
            $table->decimal('shipping_charge', 13)->nullable()->comment('The price the customer charged for shipping inc fuel');
            $table->decimal('fuel_charge', 10)->nullable()->comment('Fuel portion of price to customer');
            $table->string('sales_currency', 3)->default('GBP');
            $table->decimal('shipping_cost', 13)->nullable()->comment('Cost to IFS for shipping consignment inc fuel');
            $table->decimal('fuel_cost', 10)->nullable()->comment('Fuel portion of cost to IFS');
            $table->string('cost_currency', 3)->default('GBP');
            $table->mediumText('quoted', 16777215)->nullable()->comment('JSON array of quotation details');
            $table->boolean('carrier_pickup_required')->nullable()->default(0)->comment('Flag indicating that a carrier pickup is required');
            $table->char('alcohol_type', 1)->nullable()->comment('Indicates that some of the contents are alcohol');
            $table->char('alcohol_packaging', 2)->nullable()->comment('Alcohol packaging - bottle, carton etc.');
            $table->integer('alcohol_volume')->unsigned()->nullable()->comment('Alcohol volume in litres');
            $table->integer('alcohol_quantity')->unsigned()->nullable()->comment('Alcohol quantity - number of items');
            $table->integer('dry_ice_flag')->unsigned()->nullable()->comment('0 (no), 1 (yes), 2 (yes - bio)');
            $table->decimal('dry_ice_weight_per_package', 13)->nullable();
            $table->decimal('dry_ice_total_weight', 13)->nullable();
            $table->char('hazardous', 1)->nullable()->comment('The UN Code of the Hazardous goods');
            $table->string('external_tracking_url')->nullable()->comment('Third party tracking URL (such as link provided by easypost)');
            $table->char('sender_type', 1)->nullable()->comment('Type of sender address - (r)esidential or (c)ommercial');
            $table->string('sender_name', 50)->nullable()->comment('The name of the sender');
            $table->string('sender_company_name', 100)->nullable()->comment('Senders company');
            $table->string('sender_address1')->nullable()->comment('Senders address line 1');
            $table->string('sender_address2')->nullable()->comment('Senders address line 2');
            $table->string('sender_address3')->nullable()->comment('Senders address line 3');
            $table->string('sender_city', 50)->nullable()->comment('Senders city');
            $table->string('sender_state', 50)->nullable()->comment('Senders state');
            $table->string('sender_postcode', 15)->nullable()->comment('Senders postcode');
            $table->char('sender_country_code', 2)->nullable()->comment('Senders country code');
            $table->string('sender_telephone', 15)->nullable()->comment('Senders telephone number');
            $table->string('sender_email', 100)->nullable()->comment('Senders email address');
            $table->char('recipient_type', 1)->nullable()->comment('Type of recipient address - (r)esidential or (c)ommercial');
            $table->string('recipient_name', 50)->nullable()->comment('The name of the recipient');
            $table->string('recipient_company_name', 100)->nullable()->comment('Recipients company name');
            $table->string('recipient_address1')->nullable()->comment('Recipients address line 1');
            $table->string('recipient_address2')->nullable()->comment('Recipients address line 2');
            $table->string('recipient_address3')->nullable()->comment('Recipients address line 3');
            $table->string('recipient_city', 50)->nullable()->comment('Recipients city');
            $table->string('recipient_state', 50)->nullable()->comment('Recipients state');
            $table->string('recipient_postcode', 15)->nullable()->comment('Recipients postcode');
            $table->char('recipient_country_code', 2)->nullable()->comment('Recipients');
            $table->string('recipient_telephone', 15)->nullable()->comment('Recipients');
            $table->string('recipient_email', 100)->nullable()->comment('Recipients');
            $table->string('ship_reason', 50)->nullable()->comment('Purpose of shipment reason');
            $table->string('terms_of_sale', 50)->nullable()->comment('Terms of sale e.g FOB/FCA etc.');
            $table->char('invoice_type', 1)->nullable()->comment('(c)commercial or (p)proforma');
            $table->char('ultimate_destination_country_code', 2)->nullable()->comment('Country of ultimate destination');
            $table->string('eori')->nullable();
            $table->string('commercial_invoice_comments', 100)->nullable()->comment('Comments to append to the commercial invoice');
            $table->char('bill_shipping', 9)->nullable()->comment('Defines who will pay for the shipping costs - e.g. sender, recipient, other');
            $table->char('bill_tax_duty', 9)->nullable()->comment('Defines who will pay the duty/taxes - e.g. sender, recipient, other');
            $table->string('bill_shipping_account', 12)->nullable()->comment('A valid carrier account number relative to this consignment');
            $table->string('bill_tax_duty_account', 12)->nullable()->comment('A valid carrier account number relative to this consignment');
            $table->string('broker_name', 50)->nullable()->comment('The name of the broker');
            $table->string('broker_company_name', 100)->nullable()->comment('brokers company name');
            $table->string('broker_address1')->nullable()->comment('brokers address line 1');
            $table->string('broker_address2')->nullable()->comment('brokers address line 2');
            $table->string('broker_city', 50)->nullable()->comment('brokers city');
            $table->string('broker_state', 50)->nullable()->comment('brokers state');
            $table->string('broker_postcode', 8)->nullable()->comment('brokers postcode');
            $table->char('broker_country_code', 2)->nullable()->comment('brokers country code');
            $table->string('broker_telephone', 12)->nullable()->comment('brokers telephone');
            $table->string('broker_email', 100)->nullable()->comment('brokers email address');
            $table->string('broker_id', 100)->nullable()->comment('brokers carrier specific id');
            $table->string('broker_account', 100)->nullable()->comment('brokers carrier specific account number');
            $table->boolean('legacy')->nullable()->default(0)->comment('Flag indicating if shipment was created on legacy system');
            $table->mediumText('form_values')->nullable();
            $table->integer('user_id')->unsigned()->index('shipments_user_id_foreign')->comment('Link to the users table');
            $table->integer('company_id')->unsigned()->index('shipments_company_id_foreign')->comment('Link to the companies table');
            $table->integer('status_id')->unsigned()->nullable()->index('shipments_status_id_foreign')->comment('Link to the shipment statuses table');
            $table->integer('mode_id')->unsigned()->index('shipments_mode_id_foreign')->comment('Link to the modes of transport table');
            $table->integer('department_id')->unsigned()->nullable()->index('shipments_department_id_foreign')->comment('Link to the departments table');
            $table->integer('carrier_id')->unsigned()->nullable()->index('shipments_carrier_id_foreign')->comment('Link to the carriers table');
            $table->integer('service_id')->unsigned()->nullable()->index('shipments_service_id_foreign')->comment('Link to the services table');
            $table->integer('route_id')->unsigned()->index('shipments_route_id_foreign')->comment('Link to the routes table');
            $table->integer('depot_id')->unsigned()->index('shipments_depot_id_foreign')->comment('Link to the depots table');
            $table->integer('manifest_id')->unsigned()->nullable()->index('shipments_manifest_id_foreign')->comment('Link to the manifests table');
            $table->integer('invoice_run_id')->unsigned()->nullable()->index('shipments_invoice_run_id_foreign')->comment('Link to the pricing manifest table');
            $table->timestamp('collection_date')->nullable()->comment('Date that the customer wants the consignment to be collected');
            $table->timestamp('ship_date')->nullable()->index()->comment('Date that the shipment was despatched - value initially set to the collection date, then updated with the date received');
            $table->timestamp('delivery_date')->nullable()->comment('Date and time shipment was delivered');
            $table->timestamps();
            $table->integer('insurance_value')->nullable();
            $table->integer('lithium_batteries')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipments');
    }
}
