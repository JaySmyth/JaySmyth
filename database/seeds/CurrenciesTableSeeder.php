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
        DB::table('currencies')->insert([
            ['code' => 'EUR', 'currency' => 'Euro', 'display_order' => '3'],
            ['code' => 'HKD', 'currency' => 'HK dollar', 'display_order' => '99'],
            ['code' => 'USD', 'currency' => 'US Dollar', 'display_order' => '2'],
            ['code' => 'GBP', 'currency' => 'Sterling', 'display_order' => '1'],
            ['code' => 'CAD', 'currency' => 'Canadian Dollars', 'display_order' => '99'],
            ['code' => 'AUD', 'currency' => 'Austrailian Dollars', 'display_order' => '99'],
            ['code' => 'SFR', 'currency' => 'Swiss Francs', 'display_order' => '99'],
            ['code' => 'JYE', 'currency' => 'Japanese Yen', 'display_order' => '99'],
            ['code' => 'NZD', 'currency' => 'NewZealand Dollars', 'display_order' => '99'],
            ['code' => 'SGD', 'currency' => 'Singapore Dollars', 'display_order' => '99'],
            ['code' => 'MXN', 'currency' => 'Mexican Peso', 'display_order' => '99'],
            ['code' => 'ZAR', 'currency' => 'South African Rand', 'display_order' => '99'],
            ['code' => 'INR', 'currency' => 'India Rupie', 'display_order' => '99'],
        ]);
    }
}
