<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyPackagingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_packaging_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 20);
			$table->string('description', 50)->comment('A description of the packaging type. E.g.(pak, letter, tube)');
			$table->integer('length')->unsigned();
			$table->integer('width')->unsigned();
			$table->integer('height')->unsigned();
			$table->decimal('weight', 5);
			$table->integer('display_order')->unsigned()->comment('Integer value to control the order in which the packaging types are listed within the dropdown.');
			$table->integer('packaging_type_id')->unsigned()->nullable()->index('company_packaging_types_packaging_type_id_foreign')->comment('Identifies the equivalent IFS packaging type');
			$table->integer('company_id')->unsigned()->comment('If populated, indicates that this packaging type is owned by a specific company');
			$table->integer('mode_id')->unsigned()->index('company_packaging_types_mode_id_foreign')->comment('Indicates if this packaging type relates to courier/air/road etc.');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_packaging_types');
	}

}
