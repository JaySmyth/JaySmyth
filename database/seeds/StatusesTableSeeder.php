<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('statuses')->delete();
        
        \DB::table('statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'saved',
                'name' => 'Saved',
            'description' => 'Saved (requires completion)',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'pre_transit',
                'name' => 'Pre-Transit',
                'description' => 'Shipping Label Created',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'received',
                'name' => 'Received',
                'description' => 'Shipment Received by IFS',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'in_transit',
                'name' => 'In Transit',
                'description' => 'Shipment In Transit',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'out_for_delivery',
                'name' => 'Out For Delivery',
                'description' => 'Shipment Out For Delivery',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'delivered',
                'name' => 'Delivered',
                'description' => 'Shipment Delivered',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'cancelled',
                'name' => 'Cancelled',
                'description' => 'Shipment Cancelled',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'on_hold',
                'name' => 'On Hold',
                'description' => 'Shipment On Hold',
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'return_to_sender',
                'name' => 'Return To Sender',
                'description' => 'Shipment Returned To Sender',
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'failure',
                'name' => 'Failure',
                'description' => 'Failure',
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'unknown',
                'name' => 'Unknown',
                'description' => 'Status Unknown',
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'error',
                'name' => 'Error',
                'description' => 'Error',
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'unmanifested',
                'name' => 'Unmanifested',
                'description' => 'Unmanifested',
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'manifested',
                'name' => 'Manifested',
                'description' => 'Manifested',
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'completed',
                'name' => 'Completed',
                'description' => 'Completed',
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'collected',
                'name' => 'Collected',
                'description' => 'Collected',
            ),
            16 => 
            array (
                'id' => 17,
                'code' => 'available_for_pickup',
                'name' => 'Available For Pickup',
                'description' => 'Available For Pickup',
            ),
        ));
        
        
    }
}