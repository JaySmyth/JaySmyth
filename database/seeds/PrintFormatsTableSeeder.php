<?php

use Illuminate\Database\Seeder;

class PrintFormatsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('print_formats')->delete();
        
        \DB::table('print_formats')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'A4',
                'size' => '',
                'format' => '',
                'name' => 'A4',
                'width' => '210.00',
                'height' => '297.00',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '6X4',
                'size' => '',
                'format' => '',
                'name' => '6x4 Label',
                'width' => '102.00',
                'height' => '153.00',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '6-6X4',
                'size' => '',
                'format' => '',
                'name' => '6.6x4 Label',
                'width' => '102.00',
                'height' => '167.64',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'FEDEX',
                'size' => '',
                'format' => '',
                'name' => 'FedEx Label',
                'width' => '102.00',
                'height' => '167.64',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'LETTER',
                'size' => '',
                'format' => '',
            'name' => 'Letter (US)',
                'width' => '216.00',
                'height' => '279.00',
            ),
        ));
        
        
    }
}