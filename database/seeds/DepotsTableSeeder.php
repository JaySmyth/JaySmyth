<?php

use Illuminate\Database\Seeder;

class DepotsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('depots')->delete();
        
        \DB::table('depots')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'ANT',
                'name' => 'IFS Antrim',
                'address1' => 'IFS Global Logistics Ltd',
                'address2' => 'Seven Mile Straight',
                'address3' => '',
                'city' => 'Antrim',
                'state' => 'County Antrim',
                'postcode' => 'BT41 4QE',
                'country_code' => 'GB',
                'email' => 'transport@antrim.ifsgroup.com',
                'telephone' => '02894 464211',
                'localisation_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'LON',
                'name' => 'IFS London',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'city' => 'London',
                'state' => 'London',
                'postcode' => '',
                'country_code' => 'GB',
                'email' => 'it@antrim.ifsgroup.com',
                'telephone' => '',
                'localisation_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'MIA',
                'name' => 'ECX Miami',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'city' => 'Miami',
                'state' => 'FL',
                'postcode' => '',
                'country_code' => 'US',
                'email' => 'it@antrim.ifsgroup.com',
                'telephone' => '',
                'localisation_id' => 2,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'OLD',
                'name' => 'Redundant',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'city' => 'n/a',
                'state' => 'n/a',
                'postcode' => '',
                'country_code' => 'GB',
                'email' => 'it@antrim.ifsgroup.com',
                'telephone' => '',
                'localisation_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'DUB',
                'name' => 'Dublin',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'city' => 'Dublin',
                'state' => 'Dublin',
                'postcode' => '',
                'country_code' => 'IE',
                'email' => 'it@antrim.ifsgroup.com',
                'telephone' => '',
                'localisation_id' => 3,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'LAX',
                'name' => 'Los Angeles',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'city' => 'Buena Park',
                'state' => 'California',
                'postcode' => '',
                'country_code' => 'US',
                'email' => 'it@antrim.ifsgroup.com',
                'telephone' => '',
                'localisation_id' => 4,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'DEL',
                'name' => 'Decora London',
                'address1' => '',
                'address2' => '',
                'address3' => '',
                'city' => 'London',
                'state' => 'London',
                'postcode' => '',
                'country_code' => 'GB',
                'email' => 'it@antrim.ifsgroup.com',
                'telephone' => '',
                'localisation_id' => 1,
            ),
        ));
        
        
    }
}