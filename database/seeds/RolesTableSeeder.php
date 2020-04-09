<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'cust',
                'label' => 'Customer',
                'description' => 'Standard user access for customers.',
                'primary' => 1,
                'ifs_only' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'cusa',
                'label' => 'Customer Admin',
                'description' => 'Admin access for customers. Allows for user admin and additional reporting.',
                'primary' => 1,
                'ifs_only' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'cudv',
                'label' => 'Duty & VAT',
                'description' => 'Allows a user to view customs entries ONLY, for duty and VAT reporting purposes.',
                'primary' => 0,
                'ifs_only' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'ifsu',
                'label' => 'IFS User',
                'description' => 'Standard IFS user. Should be applied to all NON managerial employees.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'ifsm',
                'label' => 'IFS Manager',
                'description' => 'For IFS department managers. Enables company admin and additional reporting.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'ifsf',
                'label' => 'IFS Accounts',
                'description' => 'For IFS staff within the accounts department only. Allows access to purchase invoices and specific reporting.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'ifss',
                'label' => 'IFS Sales',
                'description' => 'For IFS salespersons. Allows access to their specific customers and sales reports.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'ifsc',
                'label' => 'IFS Customs',
                'description' => 'For IFS customs staff. Allows the creation of customs entries only, for Duty and VAT reporting purposes.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'ifsa',
                'label' => 'IFS Admin',
                'description' => 'For complete access to all areas of the application and all depots. Should be restricted to IT only.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'courier',
                'label' => 'Courier Shipper',
                'description' => 'Allows a user to generate a Courier shipment.',
                'primary' => 0,
                'ifs_only' => 0,
            ),
            10 => 
            array (
                'id' => 13,
                'name' => 'sea',
                'label' => 'Sea Shipper',
                'description' => 'Allows a user to generate a Sea Freight shipment.',
                'primary' => 0,
                'ifs_only' => 0,
            ),
            11 => 
            array (
                'id' => 14,
                'name' => 'ifso',
                'label' => 'IFS Sea Freight',
                'description' => 'For IFS staff within the sea freight department only.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            12 => 
            array (
                'id' => 15,
                'name' => 'ifst',
                'label' => 'IFS Transport',
                'description' => 'For IFS staff within the transport department only.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            13 => 
            array (
                'id' => 16,
                'name' => 'ifsai',
                'label' => 'IFS Air Imports',
                'description' => 'For IFS staff within the air imports department only.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            14 => 
            array (
                'id' => 17,
                'name' => 'ifsae',
                'label' => 'IFS Air Exports',
                'description' => 'For IFS staff within the air exports department only.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
            15 => 
            array (
                'id' => 18,
                'name' => 'ifsta',
                'label' => 'IFS Transport Admin',
                'description' => 'For IFS Transport Manager only.',
                'primary' => 1,
                'ifs_only' => 1,
            ),
        ));
        
        
    }
}