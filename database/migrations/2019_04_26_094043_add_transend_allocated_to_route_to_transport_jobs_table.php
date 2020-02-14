<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransendAllocatedToRouteToTransportJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_jobs', function (Blueprint $table) {
            $table->boolean('transend_allocated_to_route')->nullable()->default(0)->after('transend_account_code');
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
            $table->dropColumn('transend_allocated_to_route');
        });
    }
}
