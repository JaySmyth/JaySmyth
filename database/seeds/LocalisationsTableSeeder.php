<?php

use Illuminate\Database\Seeder;

class LocalisationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('localisations')->delete();
        
        \DB::table('localisations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'time_zone' => 'Europe/London',
                'weight_uom' => 'kg',
                'dims_uom' => 'cm',
                'date_format' => 'dd-mm-yyyy',
                'currency_code' => 'GBP',
                'document_size' => 'A4',
            ),
            1 => 
            array (
                'id' => 2,
                'time_zone' => 'America/New_York',
                'weight_uom' => 'lb',
                'dims_uom' => 'in',
                'date_format' => 'mm-dd-yyyy',
                'currency_code' => 'USD',
                'document_size' => 'LETTER',
            ),
            2 => 
            array (
                'id' => 3,
                'time_zone' => 'Europe/Dublin',
                'weight_uom' => 'kg',
                'dims_uom' => 'cm',
                'date_format' => 'dd-mm-yyyy',
                'currency_code' => 'EUR',
                'document_size' => 'A4',
            ),
            3 => 
            array (
                'id' => 4,
                'time_zone' => 'America/Los_Angeles',
                'weight_uom' => 'lb',
                'dims_uom' => 'in',
                'date_format' => 'mm-dd-yyyy',
                'currency_code' => 'USD',
                'document_size' => 'LETTER',
            ),
        ));
        
        
    }
}