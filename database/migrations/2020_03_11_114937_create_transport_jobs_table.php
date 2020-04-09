<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransportJobsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 20);
            $table->string('reference', 100)->nullable()->comment('Consignment number or reference');
            $table->integer('pieces')->unsigned()->comment('The number of packages (pieces) in the consignment.');
            $table->decimal('weight', 13)->nullable()->comment('The weight of the consignment in kg');
            $table->string('dimensions')->nullable();
            $table->string('goods_description', 100)->nullable()->comment('Description of goods to be collected/delivered');
            $table->decimal('volumetric_weight', 13)->nullable()->comment('The volumetric weight of the consignment');
            $table->string('instructions')->nullable()->comment('Special instructions for the driver');
            $table->string('pod_signature', 100)->nullable()->comment('Special instructions for the driver');
            $table->string('pod_image')->nullable();
            $table->integer('pod_user')->unsigned()->comment('User that provided the POD info');
            $table->string('scs_job_number', 15)->nullable()->comment('SCS job number to link in with SCS');
            $table->string('scs_company_code')->nullable();
            $table->decimal('cash_on_delivery', 13)->nullable()->comment('COD amount');
            $table->char('type', 1)->comment('(c)ollection or (d)elivery');
            $table->boolean('completed')->comment('Flag indicating job completion');
            $table->char('from_type', 1)->nullable()->comment('Type of collection address - (r)esidential or (c)ommercial');
            $table->string('from_name', 50)->nullable()->comment('The name of the collection');
            $table->string('from_company_name', 100)->nullable()->comment('Collection company');
            $table->string('from_address1')->nullable()->comment('Collection address line 1');
            $table->string('from_address2')->nullable()->comment('Collection address line 2');
            $table->string('from_address3')->nullable()->comment('Collection address line 3');
            $table->string('from_city', 50)->nullable()->comment('Collection city');
            $table->string('from_state', 50)->nullable()->comment('Collection state');
            $table->string('from_postcode', 8)->nullable()->comment('Collection postcode');
            $table->char('from_country_code', 2)->nullable()->comment('Collection country code');
            $table->string('from_telephone', 15)->nullable()->comment('Collection telephone number');
            $table->string('from_email', 100)->nullable()->comment('Collection email address');
            $table->char('to_type', 1)->nullable()->comment('Type of delivery address - (r)esidential or (c)ommercial');
            $table->string('to_name', 50)->nullable()->comment('The name of the delivery');
            $table->string('to_company_name', 100)->nullable()->comment('Deliveries company name');
            $table->string('to_address1')->nullable()->comment('Deliveries address line 1');
            $table->string('to_address2')->nullable()->comment('Deliveries address line 2');
            $table->string('to_address3')->nullable()->comment('Deliveries address line 3');
            $table->string('to_city', 50)->nullable()->comment('Deliveries city');
            $table->string('to_state', 50)->nullable()->comment('Deliveries state');
            $table->string('to_postcode', 8)->nullable()->comment('Deliveries postcode');
            $table->char('to_country_code', 2)->nullable()->comment('Deliveries Country Code');
            $table->string('to_telephone', 12)->nullable()->comment('Deliveries Telephone');
            $table->string('to_email', 100)->nullable()->comment('Deliveries Email');
            $table->integer('department_id')->unsigned()->nullable();
            $table->integer('depot_id')->unsigned()->index('transport_jobs_depot_id_foreign')->comment('Link to the depots table');
            $table->integer('shipment_id')->unsigned()->nullable()->index('transport_jobs_shipment_id_foreign')->comment('Link to the shipments table');
            $table->boolean('visible')->default(1);
            $table->integer('driver_manifest_id')->unsigned()->nullable()->index('transport_jobs_driver_manifest_id_foreign')->comment('Link to the collection manifests table');
            $table->integer('status_id')->unsigned()->nullable()->index('transport_jobs_status_id_foreign')->comment('Link to the statuses table');
            $table->string('final_destination', 50)->nullable();
            $table->string('closing_time', 20)->nullable();
            $table->boolean('sent')->default(0);
            $table->boolean('is_resend')->default(0);
            $table->timestamp('resend_date')->nullable();
            $table->integer('attempts')->default(0);
            $table->string('transend_route', 15)->nullable();
            $table->string('transend_account_code')->nullable();
            $table->boolean('transend_allocated_to_route')->nullable()->default(0);
            $table->timestamp('date_requested')->nullable();
            $table->timestamp('date_manifested')->nullable();
            $table->timestamp('date_completed')->nullable();
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
        Schema::drop('transport_jobs');
    }
}
