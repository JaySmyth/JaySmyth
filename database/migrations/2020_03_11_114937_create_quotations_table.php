<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuotationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name')->nullable();
            $table->string('contact', 100)->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('from_city', 100)->nullable();
            $table->char('from_country_code', 2)->nullable();
            $table->string('to_city', 100)->nullable();
            $table->char('to_country_code', 2)->nullable();
            $table->integer('pieces')->unsigned();
            $table->decimal('weight', 13)->nullable();
            $table->string('dimensions')->nullable();
            $table->decimal('volumetric_weight', 13)->nullable();
            $table->string('goods_description', 100)->nullable();
            $table->decimal('rate_of_exchange', 13, 4)->nullable();
            $table->string('terms')->nullable();
            $table->string('special_requirements')->nullable();
            $table->string('comments')->nullable();
            $table->text('information', 65535)->nullable();
            $table->decimal('quote', 13)->nullable();
            $table->char('currency_code', 3)->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->boolean('successful');
            $table->boolean('printed');
            $table->integer('department_id')->unsigned()->nullable()->index('quotations_department_id_foreign')->comment('Link to the departments table');
            $table->integer('sale_id')->unsigned()->nullable()->index('quotations_sale_id_foreign')->comment('Link to the sales table');
            $table->integer('user_id')->unsigned()->nullable()->index('quotations_user_id_foreign')->comment('Link to the users table');
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
        Schema::drop('quotations');
    }
}
