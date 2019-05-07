<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserToRfSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rf_sessions', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->after('session_id');
            $table->string('user_name')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rf_sessions', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('user_name');
        });
    }
}
