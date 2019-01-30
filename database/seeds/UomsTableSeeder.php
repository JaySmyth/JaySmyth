<?php

use Illuminate\Database\Seeder;

class UomsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {      
        //insert some dummy records
        DB::table('uoms')->insert([
            ['code' => 'BBL', 'name' => 'Barrel'],
            ['code' => 'CG', 'name' => 'CentiGrams'],
            ['code' => 'CM', 'name' => 'Centimeter'],
            ['code' => 'CM3', 'name' => 'Cubic Centimeters'],
            ['code' => 'DOZ', 'name' => 'Dozen'],
            ['code' => 'EA', 'name' => 'Each'],
            ['code' => 'FT', 'name' => 'Feet'],
            ['code' => 'G', 'name' => 'Grams'],
            ['code' => 'GAL', 'name' => 'Gallon'],
            ['code' => 'GRM', 'name' => 'Gram'],
            ['code' => 'HUN', 'name' => 'Hundreds'],
            ['code' => 'KG', 'name' => 'Kilograms'],
            ['code' => 'LB', 'name' => 'Pound'],
            ['code' => 'M', 'name' => 'Meters'],
            ['code' => 'MG', 'name' => 'Milligram'],
            ['code' => 'PR', 'name' => 'Pair'],
            ['code' => 'QT', 'name' => 'Quart'],
            ['code' => 'TOZ', 'name' => 'Troy Ounce'],
            ['code' => 'YD', 'name' => 'Yard'],
            ['code' => 'YN', 'name' => 'Yam'],
            
        ]);
    }

}
