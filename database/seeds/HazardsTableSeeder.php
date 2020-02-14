<?php

use Illuminate\Database\Seeder;

class HazardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modify a few records
        DB::table('hazards')->insert([
            ['code' => 'N', 'description' => 'None', 'mode_id' => '1'],
            ['code' => 'E', 'description' => 'Excepted Quantities', 'mode_id' => '1'],
            ['code' => 1, 'description' => 'Accessible Class 1 - Explosives', 'mode_id' => '1'],
            ['code' => 2, 'description' => 'Accessible Class 2 - Gases', 'mode_id' => '1'],
            ['code' => 3, 'description' => 'Accessible Class 3 - Flammable Liquids', 'mode_id' => '1'],
            ['code' => 4, 'description' => 'Accessible Class 4 - Flammable Solids', 'mode_id' => '1'],
            ['code' => 5, 'description' => 'Accessible Class 5 - Oxidizing Substances', 'mode_id' => '1'],
            ['code' => 6, 'description' => 'Accessible Class 6 - Toxic/Infectious Substances', 'mode_id' => '1'],
            ['code' => 7, 'description' => 'Inaccessible Class 7 - Radioactive Material', 'mode_id' => '1'],
            ['code' => 8, 'description' => 'Accessible Class 8 - Corrosives', 'mode_id' => '1'],
            ['code' => 9, 'description' => 'Inaccessible Class 9 - Miscellaneous', 'mode_id' => '1'],
        ]);
    }
}
