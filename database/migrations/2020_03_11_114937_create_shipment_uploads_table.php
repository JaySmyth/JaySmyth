<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShipmentUploadsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('directory', 100)->comment('Directory that the file will be uploaded to');
            $table->boolean('enabled')->comment('Upload enabled');
            $table->integer('total_processed')->nullable()->default(0);
            $table->integer('import_config_id')->unsigned()->index('shipment_uploads_import_config_id_foreign')->comment('Link to the import config table');
            $table->timestamp('last_upload')->nullable()->comment('Date/Time last file was processed');
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
        Schema::drop('shipment_uploads');
    }
}
