<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToImportConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_configs', function (Blueprint $table) {
            $table->string('column41', 32)->nullable()->after('column40');
            $table->string('column42', 32)->nullable()->after('column41');
            $table->string('column43', 32)->nullable()->after('column42');
            $table->string('column44', 32)->nullable()->after('column43');
            $table->string('column45', 32)->nullable()->after('column44');
            $table->string('column46', 32)->nullable()->after('column45');
            $table->string('column47', 32)->nullable()->after('column46');
            $table->string('column48', 32)->nullable()->after('column47');
            $table->string('column49', 32)->nullable()->after('column48');
            $table->string('column50', 32)->nullable()->after('column49');
            $table->string('column51', 32)->nullable()->after('column50');
            $table->string('column52', 32)->nullable()->after('column51');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_configs', function (Blueprint $table) {
            $table->dropColumn(['column41']);
            $table->dropColumn(['column42']);
            $table->dropColumn(['column43']);
            $table->dropColumn(['column44']);
            $table->dropColumn(['column45']);
            $table->dropColumn(['column46']);
            $table->dropColumn(['column47']);
            $table->dropColumn(['column48']);
            $table->dropColumn(['column49']);
            $table->dropColumn(['column50']);
            $table->dropColumn(['column51']);
            $table->dropColumn(['column52']);
        });
    }
}
