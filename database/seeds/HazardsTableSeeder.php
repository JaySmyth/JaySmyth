<?php

use Illuminate\Database\Seeder;

class HazardsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('hazards')->delete();
        
        \DB::table('hazards')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'N',
                'description' => 'None',
                'mode_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'E',
                'description' => 'Excepted Quantities',
                'mode_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '1',
                'description' => 'Accessible Class 1 - Explosives',
                'mode_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => '2',
                'description' => 'Accessible Class 2 - Gases',
                'mode_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => '3',
                'description' => 'Accessible Class 3 - Flammable Liquids',
                'mode_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => '4',
                'description' => 'Accessible Class 4 - Flammable Solids',
                'mode_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => '5',
                'description' => 'Accessible Class 5 - Oxidizing Substances',
                'mode_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => '6',
                'description' => 'Inaccessible Class 6 - Toxic/Infectious Substances',
                'mode_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'code' => '7',
                'description' => 'Inaccessible Class 7 - Radioactive Material',
                'mode_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'code' => '8',
                'description' => 'Accessible Class 8 - Corrosives',
                'mode_id' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'code' => '9',
                'description' => 'Inaccessible Class 9 - Miscellaneous',
                'mode_id' => 1,
            ),
        ));
        
        
    }
}