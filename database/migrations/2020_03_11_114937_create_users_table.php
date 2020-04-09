<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('password');
            $table->integer('label_copies')->unsigned()->default(0)->comment('Number of label copies to print');
            $table->string('remember_token', 100)->nullable();
            $table->string('api_token', 60)->unique();
            $table->boolean('enabled');
            $table->boolean('show_search_bar')->comment('Defines if search bar (if available) should be displayed by default');
            $table->integer('localisation_id')->unsigned()->comment('Link to the localisations table');
            $table->integer('print_format_id')->unsigned()->comment('Link to the print formats table (default print format)');
            $table->boolean('customer_label');
            $table->boolean('driver_label');
            $table->string('browser', 50)->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('screen_resolution', 20)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
