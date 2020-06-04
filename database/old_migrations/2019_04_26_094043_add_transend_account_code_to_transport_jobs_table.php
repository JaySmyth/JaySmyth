<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransendAccountCodeToTransportJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_jobs', function (Blueprint $table) {
            $table->string('transend_account_code')->nullable()->after('transend_route');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_jobs', function (Blueprint $table) {
            $table->dropColumn('transend_account_code');
        });
    }
}
