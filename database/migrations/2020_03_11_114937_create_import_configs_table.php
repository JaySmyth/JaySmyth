<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImportConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import_configs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('company_id')->index('mode_id');
			$table->string('company_name', 50);
			$table->integer('mode_id');
			$table->string('column0', 32)->nullable();
			$table->string('column1', 32)->nullable();
			$table->string('column2', 32)->nullable();
			$table->string('column3', 32)->nullable();
			$table->string('column4', 32)->nullable();
			$table->string('column5', 32)->nullable();
			$table->string('column6', 32)->nullable();
			$table->string('column7', 32)->nullable();
			$table->string('column8', 32)->nullable();
			$table->string('column9', 32)->nullable();
			$table->string('column10', 32)->nullable();
			$table->string('column11', 32)->nullable();
			$table->string('column12', 32)->nullable();
			$table->string('column13', 32)->nullable();
			$table->string('column14', 32)->nullable();
			$table->string('column15', 32)->nullable();
			$table->string('column16', 32)->nullable();
			$table->string('column17', 32)->nullable();
			$table->string('column18', 32)->nullable();
			$table->string('column19', 32)->nullable();
			$table->string('column20', 32)->nullable();
			$table->string('column21', 32)->nullable();
			$table->string('column22', 32)->nullable();
			$table->string('column23', 32)->nullable();
			$table->string('column24', 32)->nullable();
			$table->string('column25', 32)->nullable();
			$table->string('column26', 32)->nullable();
			$table->string('column27', 32)->nullable();
			$table->string('column28', 32)->nullable();
			$table->string('column29', 32)->nullable();
			$table->string('column30', 32)->nullable();
			$table->string('column31', 32)->nullable();
			$table->string('column32', 32)->nullable();
			$table->string('column33', 32)->nullable();
			$table->string('column34', 32)->nullable();
			$table->string('column35', 32)->nullable();
			$table->string('column36', 32)->nullable();
			$table->string('column37', 32)->nullable();
			$table->string('column38', 32)->nullable();
			$table->string('column39', 32)->nullable();
			$table->string('column40', 32)->nullable();
			$table->string('column41', 32)->nullable();
			$table->string('column42', 32)->nullable();
			$table->string('column43', 32)->nullable();
			$table->string('column44', 32)->nullable();
			$table->string('column45', 32)->nullable();
			$table->string('column46', 32)->nullable();
			$table->string('column47', 32)->nullable();
			$table->string('column48', 32)->nullable();
			$table->string('column49', 32)->nullable();
			$table->string('column50', 32)->nullable();
			$table->string('column51', 32)->nullable();
			$table->string('column52', 32)->nullable();
			$table->text('fields', 65535);
			$table->string('delim', 10);
			$table->boolean('enabled');
			$table->boolean('test_mode');
			$table->integer('start_row');
			$table->text('resp_fields', 65535);
			$table->boolean('resp_headings');
			$table->string('ship_ref_sep', 10);
			$table->string('default_service', 10);
			$table->string('default_terms', 5)->nullable();
			$table->integer('default_pieces');
			$table->string('default_goods_description', 50);
			$table->string('default_recipient_name', 50);
			$table->string('default_recipient_telephone', 25)->nullable();
			$table->string('default_recipient_email', 150);
			$table->decimal('default_weight');
			$table->decimal('default_customs_value');
			$table->string('terms', 3)->nullable();
			$table->string('cc_import_results_email', 150)->nullable();
			$table->timestamps();
			$table->boolean('third_party');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('import_configs');
	}

}
