<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            ['code' => 'saved', 'name' => 'Saved', 'description' => 'Saved (requires completion)'],            
            ['code' => 'pre_transit', 'name' => 'Pre-Transit', 'description' => 'Shipping Label Created'],                        
            ['code' => 'received', 'name' => 'Received', 'description' => 'Shipment Received by IFS'],
            ['code' => 'in_transit', 'name' => 'In Transit', 'description' => 'Shipment In Transit'],
            ['code' => 'out_for_delivery', 'name' => 'Out For Delivery', 'description' => 'Shipment Out For Delivery'],
            ['code' => 'delivered', 'name' => 'Delivered', 'description' => 'Shipment Delivered'],
            ['code' => 'cancelled', 'name' => 'Cancelled', 'description' => 'Shipment Cancelled'],            
            ['code' => 'on_hold', 'name' => 'On Hold', 'description' => 'Shipment On Hold'],
            ['code' => 'return_to_sender', 'name' => 'Return To Sender', 'description' => 'Shipment Returned To Sender'],
            ['code' => 'failure', 'name' => 'Delivery Failure', 'description' => 'Delivery Failure']
        ]);
    }

}