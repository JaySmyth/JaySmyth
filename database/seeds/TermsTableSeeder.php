<?php

use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('terms')->delete();
        
        \DB::table('terms')->insert(array (
            0 => 
            array (
                'id' => 2,
                'code' => 'FCA',
                'description' => 'Free Carrier',
                'mode_id' => 1,
            ),
            1 => 
            array (
                'id' => 3,
                'code' => 'CPT',
                'description' => 'Carriage Paid To',
                'mode_id' => 1,
            ),
            2 => 
            array (
                'id' => 4,
                'code' => 'CIP',
                'description' => 'Carriage & Ins Paid to',
                'mode_id' => 1,
            ),
            3 => 
            array (
                'id' => 5,
                'code' => 'DAT',
                'description' => 'Delivered At Terminal',
                'mode_id' => 1,
            ),
            4 => 
            array (
                'id' => 6,
                'code' => 'DAP',
                'description' => 'Delivered At Place',
                'mode_id' => 1,
            ),
            5 => 
            array (
                'id' => 7,
                'code' => 'DDP',
                'description' => 'Delivered Duty Paid',
                'mode_id' => 1,
            ),
            6 => 
            array (
                'id' => 8,
                'code' => 'FAS',
                'description' => 'Free Alongside Ship',
                'mode_id' => 4,
            ),
            7 => 
            array (
                'id' => 9,
                'code' => 'FOB',
                'description' => 'Free On Board',
                'mode_id' => 4,
            ),
            8 => 
            array (
                'id' => 10,
                'code' => 'CFR',
                'description' => 'Cost & Freight',
                'mode_id' => 4,
            ),
            9 => 
            array (
                'id' => 11,
                'code' => 'CIF',
                'description' => 'Cost, Insurance & Freight',
                'mode_id' => 4,
            ),
            10 => 
            array (
                'id' => 12,
                'code' => 'EXW',
                'description' => 'Ex Works',
                'mode_id' => 1,
            ),
        ));
        
        
    }
}