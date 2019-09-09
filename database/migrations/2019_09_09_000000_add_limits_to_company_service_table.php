<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLimitsToCompanyServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_service', function (Blueprint $table) {
            $table->integer('monthly_limit')->unsigned()->nullable()->after('country_filter');
            $table->integer('max_weight_limit')->unsigned()->nullable()->after('monthly_limit');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_service', function (Blueprint $table) {
            $table->dropColumn('monthly_limit');
            $table->dropColumn('max_weight_limit');
        });
    }
}
