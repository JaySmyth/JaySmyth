<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToFedexEasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fedex_eas', function (Blueprint $table) {
            $table->string('type', '3')->after('locn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fedex_eas', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
