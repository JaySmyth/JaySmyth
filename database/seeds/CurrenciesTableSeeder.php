<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('currencies')->delete();
        
        \DB::table('currencies')->insert(array (
            0 => 
            array (
                'code' => 'AUD',
                'currency' => 'Austrailian Dollars',
                'display_order' => 99,
                'id' => 1,
                'rate' => '1.9213',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:21:07',
            ),
            1 => 
            array (
                'code' => 'CAD',
                'currency' => 'Canadian Dollars',
                'display_order' => 99,
                'id' => 2,
                'rate' => '1.7239',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:21:28',
            ),
            2 => 
            array (
                'code' => 'EUR',
                'currency' => 'Euro',
                'display_order' => 3,
                'id' => 3,
                'rate' => '1.1864',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:20:44',
            ),
            3 => 
            array (
                'code' => 'GBP',
                'currency' => 'Sterling',
                'display_order' => 1,
                'id' => 4,
                'rate' => '1.0000',
                'created_at' => NULL,
                'updated_at' => '2017-04-03 15:17:28',
            ),
            4 => 
            array (
                'code' => 'HKD',
                'currency' => 'HK dollar',
                'display_order' => 99,
                'id' => 5,
                'rate' => '10.2100',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:22:11',
            ),
            5 => 
            array (
                'code' => 'INR',
                'currency' => 'India Rupie',
                'display_order' => 99,
                'id' => 6,
                'rate' => '93.5500',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:22:29',
            ),
            6 => 
            array (
                'code' => 'JYE',
                'currency' => 'Japanese Yen',
                'display_order' => 99,
                'id' => 7,
                'rate' => '144.4200',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:23:08',
            ),
            7 => 
            array (
                'code' => 'MXN',
                'currency' => 'Mexican Peso',
                'display_order' => 99,
                'id' => 8,
                'rate' => '24.5500',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:23:37',
            ),
            8 => 
            array (
                'code' => 'NZD',
                'currency' => 'NewZealand Dollars',
                'display_order' => 99,
                'id' => 9,
                'rate' => '1.9929',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:23:57',
            ),
            9 => 
            array (
                'code' => 'SFR',
                'currency' => 'Swiss Francs',
                'display_order' => 99,
                'id' => 10,
                'rate' => '1.2748',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:25:28',
            ),
            10 => 
            array (
                'code' => 'SGD',
                'currency' => 'Singapore Dollars',
                'display_order' => 99,
                'id' => 11,
                'rate' => '1.7731',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:24:20',
            ),
            11 => 
            array (
                'code' => 'USD',
                'currency' => 'US Dollar',
                'display_order' => 2,
                'id' => 12,
                'rate' => '1.3140',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:20:10',
            ),
            12 => 
            array (
                'code' => 'ZAR',
                'currency' => 'South African Rand',
                'display_order' => 99,
                'id' => 13,
                'rate' => '18.8400',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:24:47',
            ),
            13 => 
            array (
                'code' => 'CLP',
                'currency' => 'Chilean Peso',
                'display_order' => 99,
                'id' => 14,
                'rate' => '1014.6300',
                'created_at' => '2017-01-26 09:55:37',
                'updated_at' => '2020-02-03 11:21:46',
            ),
            14 => 
            array (
                'code' => 'TWD',
                'currency' => 'Taiwan dollar',
                'display_order' => 99,
                'id' => 15,
                'rate' => '39.3900',
                'created_at' => '2017-04-03 10:35:52',
                'updated_at' => '2020-02-03 11:26:10',
            ),
            15 => 
            array (
                'code' => 'KRW',
                'currency' => 'South Korean Won',
                'display_order' => 99,
                'id' => 16,
                'rate' => '1530.2600',
                'created_at' => '2017-04-03 10:37:33',
                'updated_at' => '2020-02-03 11:25:10',
            ),
            16 => 
            array (
                'code' => 'NIS',
                'currency' => 'Israeli Shekel',
                'display_order' => 99,
                'id' => 17,
                'rate' => '4.5495',
                'created_at' => NULL,
                'updated_at' => '2020-02-03 11:22:50',
            ),
        ));
        
        
    }
}