<?php

use Illuminate\Database\Seeder;

class VatCodesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('vat_codes')->delete();
        
        \DB::table('vat_codes')->insert(array (
            0 => 
            array (
                'id' => 4,
                'code' => 'z',
                'percent' => '0.00',
                'from_date' => '2016-01-01',
                'to_date' => '2099-12-31',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 5,
                'code' => 'e',
                'percent' => '0.00',
                'from_date' => '2016-01-01',
                'to_date' => '2099-12-31',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 6,
                'code' => '1',
                'percent' => '20.00',
                'from_date' => '2016-01-01',
                'to_date' => '2099-12-31',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}