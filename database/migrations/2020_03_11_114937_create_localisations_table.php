<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocalisationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('localisations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('time_zone', 20)->comment('Name of the localisation - e.g Europe/London');
			$table->string('weight_uom', 3)->comment('Weight unit of measure - e.g. kg, lbs');
			$table->string('dims_uom', 4)->comment('Dims unit of measure - e.g. cm, inch');
			$table->string('date_format', 10)->comment('Date format e.g. dd-mm-yy or mm-dd-yy');
			$table->char('currency_code', 3)->comment('Default currency code for the region');
			$table->string('document_size', 6)->comment('Default document size for the region. e.g. A4 or LETTER');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('localisations');
	}

}
