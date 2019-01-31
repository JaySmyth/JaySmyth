<?php

use Illuminate\Database\Seeder;

class LocalisationsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('localisations')->insert([
            ['time_zone' => 'Europe/London', 'weight_uom' => 'kg', 'dims_uom' => 'cm', 'date_format' => 'dd-mm-yyyy', 'currency_code' => 'GBP', 'document_size' => 'A4'],
            ['time_zone' => 'America/New_York', 'weight_uom' => 'lb', 'dims_uom' => 'inch', 'date_format' => 'mm-dd-yyyy', 'currency_code' => 'USD', 'document_size' => 'LETTER']
        ]);
    }

}