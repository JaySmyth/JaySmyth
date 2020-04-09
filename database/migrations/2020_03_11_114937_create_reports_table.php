<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100)->comment('Report name');
			$table->string('description', 150)->comment('Report description');
			$table->string('route', 50)->comment('Controller endpoint');
			$table->string('permission', 50)->comment('Permission name to access this report');
			$table->string('criteria')->comment('Json string holding report criteria');
			$table->integer('depot_id')->unsigned()->nullable()->index('reports_depot_id_foreign')->comment('Link to the depots table');
			$table->integer('mode_id')->unsigned()->nullable()->index('reports_mode_id_foreign')->comment('Link to the modes table');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reports');
	}

}
