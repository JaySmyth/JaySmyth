<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDhlEasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhl_eas', function (Blueprint $table) {
            $table->index(['recipient_country_code', 'recipient_town', 'recipient_postcode'], 'country_town_postcode');
            $table->index(['recipient_country_code', 'recipient_postcode'], 'country_postcode');
            $table->index(['recipient_country_code', 'recipient_town'], 'country_town');
            $table->index(['recipient_country_code'], 'country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dhl_eas', function (Blueprint $table) {
            $table->dropIndex('country_town_postcode');
            $table->dropIndex('country_postcode');
            $table->dropIndex('country_town');
            $table->dropIndex('country');
        });
    }
}
