<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricingZonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pricing_zones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id');
			$table->integer('model_id');
			$table->char('sender_country_code', 2);
			$table->string('from_sender_postcode', 10);
			$table->string('to_sender_postcode', 10);
			$table->char('recipient_country_code', 2);
			$table->string('recipient_name', 50);
			$table->string('from_recipient_postcode', 10);
			$table->string('to_recipient_postcode', 10);
			$table->string('service_code', 8);
			$table->char('cost_zone', 3);
			$table->char('sale_zone', 3);
			$table->date('from_date');
			$table->date('to_date');
			$table->timestamps();
			$table->index(['model_id','company_id','sender_country_code','from_sender_postcode','to_sender_postcode','service_code','recipient_country_code','from_recipient_postcode','to_recipient_postcode'], 'Index1');
			$table->index(['model_id','company_id','sender_country_code','service_code','recipient_country_code','from_recipient_postcode','to_recipient_postcode'], 'Index3');
			$table->index(['model_id','company_id','sender_country_code','from_sender_postcode','to_sender_postcode','service_code','recipient_country_code'], 'Index2');
			$table->index(['model_id','company_id','sender_country_code','service_code','recipient_country_code'], 'Index4');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pricing_zones');
	}

}
