<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailReportRecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mail_report_recipients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->comment('Descriptive name for the report');
			$table->string('to')->comment('To. address');
			$table->string('bcc')->comment('Bcc. address');
			$table->char('format', 4)->comment('csv, xls, xlxs, pdf, html');
			$table->string('criteria')->comment('Json string holding report criteria');
			$table->string('frequency', 20)->comment('Hourly / Daily / Weekly / Monthly');
			$table->string('time', 11)->comment('Time that the report should be run');
			$table->boolean('enabled');
			$table->integer('mail_report_id')->unsigned()->index('mail_report_recipients_mail_report_id_foreign');
			$table->dateTime('last_run')->nullable()->comment('Date/Time last run');
			$table->dateTime('next_run')->nullable()->comment('Date/Time of next run');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mail_report_recipients');
	}

}
