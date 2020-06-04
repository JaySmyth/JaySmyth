<?php

use Illuminate\Database\Seeder;

class UomsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('uoms')->delete();
        
        \DB::table('uoms')->insert(array(
            0 =>
            array(
                'id' => 1,
                'code' => 'BBL',
                'name' => 'Barrel',
            ),
            1 =>
            array(
                'id' => 2,
                'code' => 'CG',
                'name' => 'CentiGrams',
            ),
            2 =>
            array(
                'id' => 3,
                'code' => 'CM',
                'name' => 'Centimeter',
            ),
            3 =>
            array(
                'id' => 4,
                'code' => 'CM3',
                'name' => 'Cubic Centimeters',
            ),
            4 =>
            array(
                'id' => 5,
                'code' => 'DOZ',
                'name' => 'Dozen',
            ),
            5 =>
            array(
                'id' => 6,
                'code' => 'EA',
                'name' => 'Each',
            ),
            6 =>
            array(
                'id' => 7,
                'code' => 'FT',
                'name' => 'Feet',
            ),
            7 =>
            array(
                'id' => 8,
                'code' => 'G',
                'name' => 'Grams',
            ),
            8 =>
            array(
                'id' => 9,
                'code' => 'GAL',
                'name' => 'Gallon',
            ),
            9 =>
            array(
                'id' => 10,
                'code' => 'GRM',
                'name' => 'Gram',
            ),
            10 =>
            array(
                'id' => 11,
                'code' => 'HUN',
                'name' => 'Hundreds',
            ),
            11 =>
            array(
                'id' => 12,
                'code' => 'KG',
                'name' => 'Kilograms',
            ),
            12 =>
            array(
                'id' => 13,
                'code' => 'LB',
                'name' => 'Pound',
            ),
            13 =>
            array(
                'id' => 14,
                'code' => 'M',
                'name' => 'Meters',
            ),
            14 =>
            array(
                'id' => 15,
                'code' => 'MG',
                'name' => 'Milligram',
            ),
            15 =>
            array(
                'id' => 16,
                'code' => 'PR',
                'name' => 'Pair',
            ),
            16 =>
            array(
                'id' => 17,
                'code' => 'QT',
                'name' => 'Quart',
            ),
            17 =>
            array(
                'id' => 18,
                'code' => 'TOZ',
                'name' => 'Troy Ounce',
            ),
            18 =>
            array(
                'id' => 19,
                'code' => 'YD',
                'name' => 'Yard',
            ),
            19 =>
            array(
                'id' => 20,
                'code' => 'YN',
                'name' => 'Yam',
            ),
            20 =>
            array(
                'id' => 21,
                'code' => 'L',
                'name' => 'Litre',
            ),
            21 =>
            array(
                'id' => 22,
                'code' => 'ML',
                'name' => 'MilliLitre',
            ),
        ));
    }
}
