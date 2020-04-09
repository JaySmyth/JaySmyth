<?php

use Illuminate\Database\Seeder;

class ProblemEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('problem_events')->delete();
        
        \DB::table('problem_events')->insert(array (
            0 => 
            array (
                'id' => 1,
                'event' => 'Refused By Consignee',
                'relevance' => 's,b,o,d',
            ),
            1 => 
            array (
                'id' => 2,
                'event' => 'Service Not Available In Pcode',
                'relevance' => 's,b,o,d',
            ),
            2 => 
            array (
                'id' => 3,
                'event' => 'Delivery Attempted-Card Left',
                'relevance' => 's,b,o,d',
            ),
            3 => 
            array (
                'id' => 4,
                'event' => 'Book In Or No Access To Site',
                'relevance' => 's,b,o,d',
            ),
            4 => 
            array (
                'id' => 5,
                'event' => 'Hub Mis-Sort',
                'relevance' => 's,b,o,d',
            ),
            5 => 
            array (
                'id' => 6,
                'event' => 'Address Query',
                'relevance' => 's,b,o,d',
            ),
            6 => 
            array (
                'id' => 7,
                'event' => 'Goods Not Received At Del Depot',
                'relevance' => 's,b,o,d',
            ),
            7 => 
            array (
                'id' => 8,
                'event' => 'Incorrect Address',
                'relevance' => 's,b,o,d',
            ),
            8 => 
            array (
                'id' => 9,
                'event' => 'Refused By Recipient',
                'relevance' => 's,b,o,d',
            ),
            9 => 
            array (
                'id' => 10,
                'event' => 'Future Delivery Requested',
                'relevance' => 's,b,o,d',
            ),
            10 => 
            array (
                'id' => 11,
                'event' => 'Shipment Cancelled By Sender',
                'relevance' => 's,b,o,d',
            ),
            11 => 
            array (
                'id' => 12,
                'event' => 'Package At Station. Arrived After Courier Dispatch',
                'relevance' => 's,b,o,d',
            ),
            12 => 
            array (
                'id' => 13,
                'event' => 'Customer Not Available or Business Closed',
                'relevance' => 's,b,o,d',
            ),
            13 => 
            array (
                'id' => 14,
                'event' => 'Local Delivery Restriction. Delivery Not Attempted',
                'relevance' => 's,b,o,d',
            ),
        ));
        
        
    }
}