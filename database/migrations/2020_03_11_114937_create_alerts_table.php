<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlertsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->char('type', 1)->comment('(s)ender / (r)ecpient');
            $table->boolean('despatched');
            $table->boolean('collected')->comment('Flag indicating if the user has requested notification for this event');
            $table->boolean('out_for_delivery')->comment('Flag indicating if the user has requested notification for this event');
            $table->boolean('delivered')->comment('Flag indicating if the user has requested notification for this event');
            $table->boolean('cancelled')->comment('Flag indicating if the user has requested notification for this event');
            $table->boolean('problems')->comment('Flag indicating if the user has requested notification for this event');
            $table->boolean('despatched_sent');
            $table->boolean('collected_sent')->comment('Flag indicating if the email has been sent');
            $table->boolean('out_for_delivery_sent')->comment('Flag indicating if the email has been sent');
            $table->boolean('delivered_sent')->comment('Flag indicating if the email has been sent');
            $table->boolean('cancelled_sent')->comment('Flag indicating if the email has been sent');
            $table->longText('problems_sent')->nullable()->comment('Logs the problems that notifications have been sent for - separated by a pipe symbol');
            $table->timestamp('despatched_sent_at')->nullable();
            $table->timestamp('collected_sent_at')->nullable()->comment('Time that the email was queued for processing');
            $table->timestamp('out_for_delivery_sent_at')->nullable()->comment('Time that the email was queued for processing');
            $table->timestamp('delivered_sent_at')->nullable()->comment('Time that the email was queued for processing');
            $table->timestamp('cancelled_sent_at')->nullable()->comment('Time that the email was queued for processing');
            $table->integer('shipment_id')->unsigned()->index('alerts_shipment_id_foreign');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alerts');
    }
}
