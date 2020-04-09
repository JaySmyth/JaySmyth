<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateManifestProfilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('Descriptive name for the manifest profile e.g FedEx International');
            $table->string('prefix', 4)->comment('Prefix to use on manifest numbers.');
            $table->integer('depot_id')->unsigned()->index('manifest_profiles_depot_id_foreign')->comment('Link to the depots table');
            $table->integer('mode_id')->unsigned()->index('manifest_profiles_mode_id_foreign')->comment('Link to the modes table');
            $table->integer('carrier_id')->unsigned()->index('manifest_profiles_carrier_id_foreign')->comment('Link to the carriers table');
            $table->integer('route_id')->unsigned()->nullable()->index('manifest_profiles_route_id_foreign')->comment('Link to the routes table');
            $table->boolean('collect_shipments_only')->default(0);
            $table->boolean('exclude_collect_shipments')->default(0);
            $table->boolean('auto')->comment('Flag indicating if this manifest profile should be run automatically');
            $table->string('time', 5)->comment('Time that the manifest should be ran automatically');
            $table->boolean('upload_required')->comment('Flag indicating if manifests should be uploaded to a carrier');
            $table->timestamp('last_run')->nullable()->comment('Date/time that this manifest profile was last run');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('manifest_profiles');
    }
}
