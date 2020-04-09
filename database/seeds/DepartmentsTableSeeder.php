<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('departments')->delete();
        
        \DB::table('departments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'IFCEX',
                'transend_code' => 'ROAD',
                'name' => 'Courier Export',
                'scs_company_code' => '05',
                'email' => 'alerts.international@antrim.ifsgroup.com',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'IFCIM',
                'transend_code' => 'ROAD',
                'name' => 'Courier Import',
                'scs_company_code' => '05',
                'email' => 'alerts.international@antrim.ifsgroup.com',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'IFFRD',
                'transend_code' => 'ROAD',
                'name' => 'Road Domestic',
                'scs_company_code' => '02',
                'email' => 'transport@antrim.ifsgroup.com',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'IFFRI',
                'transend_code' => 'ROAD',
                'name' => 'Road Import',
                'scs_company_code' => '02',
                'email' => 'transport@antrim.ifsgroup.com',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'IFFRX',
                'transend_code' => 'ROAD',
                'name' => 'Road Export',
                'scs_company_code' => '02',
                'email' => 'transport@antrim.ifsgroup.com',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'IFFAI',
                'transend_code' => 'AIR',
                'name' => 'Air Import',
                'scs_company_code' => '02',
                'email' => 'airimports@antrim.ifsgroup.com',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'IFFAX',
                'transend_code' => 'AIR',
                'name' => 'Air Export',
                'scs_company_code' => '02',
                'email' => 'airexports@antrim.ifsgroup.com',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'IFFSI',
                'transend_code' => 'SEA',
                'name' => 'Sea Import',
                'scs_company_code' => '02',
                'email' => 'seafreight@antrim.ifsgroup.com',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'IFFSX',
                'transend_code' => 'SEA',
                'name' => 'Sea Export',
                'scs_company_code' => '02',
                'email' => 'seafreight@antrim.ifsgroup.com',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'IFCUK',
                'transend_code' => 'ROAD',
                'name' => 'Courier UK',
                'scs_company_code' => '02',
                'email' => 'alerts.domestic@antrim.ifsgroup.com',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'EUROX',
                'transend_code' => 'ROAD',
                'name' => 'EU Road',
                'scs_company_code' => '02',
                'email' => 'transport@antrim.ifsgroup.com',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'IFSTP',
                'transend_code' => 'ROAD',
                'name' => 'Transport',
                'scs_company_code' => '02',
                'email' => 'transport@antrim.ifsgroup.com',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'EUROI',
                'transend_code' => 'ROAD',
                'name' => 'EU ROI',
                'scs_company_code' => '02',
                'email' => 'transport@antrim.ifsgroup.com',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'BEAAI',
                'transend_code' => 'AIR',
                'name' => 'BE Aerospace Air Imports',
                'scs_company_code' => '02',
                'email' => 'airimports@antrim.ifsgroup.com',
            ),
        ));
        
        
    }
}