<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('domain', 50);
            $table->string('secret', 128)->nullable();
            $table->string('username', 128)->nullable();
            $table->string('password', 128)->nullable();
            $table->string('location', 20)->nullable();
            $table->string('company_id')->nullable();
            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('shopify_accounts');
    }
}
