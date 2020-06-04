<?php

use Illuminate\Database\Seeder;

class ModesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('modes')->delete();
        
        \DB::table('modes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'courier',
                'label' => 'Courier',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'air',
                'label' => 'Air Freight',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'road',
                'label' => 'Road Freight',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'sea',
                'label' => 'Sea Freight',
            ),
        ));
        
        
    }
}