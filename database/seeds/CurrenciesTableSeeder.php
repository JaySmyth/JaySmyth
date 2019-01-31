<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modify a few records
        DB::table('currencies')->insert(array(
            array('code' => 'EUR', 'currency' => 'Euro', 'display_order' => '3'),
            array('code' => 'HKD', 'currency' => 'HK dollar', 'display_order' => '99'),
            array('code' => 'USD', 'currency' => 'US Dollar', 'display_order' => '2'),
            array('code' => 'GBP', 'currency' => 'Sterling', 'display_order' => '1'),
            array('code' => 'CAD', 'currency' => 'Canadian Dollars', 'display_order' => '99'),
            array('code' => 'AUD', 'currency' => 'Austrailian Dollars', 'display_order' => '99'),
            array('code' => 'SFR', 'currency' => 'Swiss Francs', 'display_order' => '99'),
            array('code' => 'JYE', 'currency' => 'Japanese Yen', 'display_order' => '99'),
            array('code' => 'NZD', 'currency' => 'NewZealand Dollars', 'display_order' => '99'),
            array('code' => 'SGD', 'currency' => 'Singapore Dollars', 'display_order' => '99'),
            array('code' => 'MXN', 'currency' => 'Mexican Peso', 'display_order' => '99'),
            array('code' => 'ZAR', 'currency' => 'South African Rand', 'display_order' => '99'),
            array('code' => 'INR', 'currency' => 'India Rupie', 'display_order' => '99'),
        ));
    }
}
