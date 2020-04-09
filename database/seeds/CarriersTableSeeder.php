<?php

use Illuminate\Database\Seeder;

class CarriersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('carriers')->delete();
        
        \DB::table('carriers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'ifs',
                'name' => 'IFS',
                'scs_carrier_code' => 'IFS',
                'easypost' => '***',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'fedex',
                'name' => 'FedEx',
                'scs_carrier_code' => 'FX',
                'easypost' => 'FedEx',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'ups',
                'name' => 'UPS',
                'scs_carrier_code' => 'UPS',
                'easypost' => '***',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'tnt',
                'name' => 'TNT',
                'scs_carrier_code' => 'TNT',
                'easypost' => 'TNTExpress',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'dhl',
                'name' => 'DHL',
                'scs_carrier_code' => 'DH',
                'easypost' => 'DHLExpress',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'rm',
                'name' => 'Royal Mail',
                'scs_carrier_code' => NULL,
                'easypost' => 'RoyalMail',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'pf',
                'name' => 'Parcelforce',
                'scs_carrier_code' => NULL,
                'easypost' => 'Parcelforce',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'ps',
                'name' => 'Pallet Service Carrier',
                'scs_carrier_code' => 'DH',
                'easypost' => '***',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'dhlmail',
                'name' => 'DHL Global Mail',
                'scs_carrier_code' => NULL,
                'easypost' => 'DHLGlobalMail',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'cwide',
                'name' => 'Countrywide',
                'scs_carrier_code' => NULL,
                'easypost' => '***',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'usps',
                'name' => 'US Postal Service',
                'scs_carrier_code' => 'USP',
                'easypost' => 'USPS',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'pri',
                'name' => 'Primary Freight',
                'scs_carrier_code' => NULL,
                'easypost' => '***',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'dim',
                'name' => 'Dimerco',
                'scs_carrier_code' => NULL,
                'easypost' => '***',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'exp',
                'name' => 'Express Freight ROI',
                'scs_carrier_code' => NULL,
                'easypost' => '***',
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'expni',
                'name' => 'Express Freight NI',
                'scs_carrier_code' => NULL,
                'easypost' => '***',
            ),
        ));
        
        
    }
}