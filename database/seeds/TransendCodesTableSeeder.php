<?php

use Illuminate\Database\Seeder;

class TransendCodesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transend_codes')->delete();
        
        \DB::table('transend_codes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'SABC01',
                'description' => 'Not my area',
                'resend' => 1,
                'resend_same_day' => 1,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'SABC02',
                'description' => 'No time to cover collection',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'SABC03',
                'description' => 'Customer premises closed',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'SABC04',
                'description' => 'Cancelled by customer',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'SABC06',
                'description' => 'Vehicle breakdown',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'SABC07',
                'description' => 'Capacity issue',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'SABC08',
                'description' => 'Weight issue',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'SABC09',
                'description' => 'Passed that area',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'SABC10',
                'description' => 'Freight already collected',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'SABC11',
                'description' => 'Goods not ready',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'SABD01',
                'description' => 'Customer not home',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'SABD02',
                'description' => 'Cant locate address',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'SABD03',
                'description' => 'Refused',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'SABD06',
                'description' => 'Customer never ordered',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'SABD09',
                'description' => 'Vehicle breakdown',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'JNBC01',
                'description' => 'Cancelled by customer',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'code' => 'JNBC03',
                'description' => 'Freight already collected',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'code' => 'JNBC04',
                'description' => 'Goods not ready',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'code' => 'JNBD01',
                'description' => 'Refused',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'code' => 'JNBD02',
                'description' => 'Customer never ordered',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'code' => 'SNBD01',
                'description' => 'Vehicle breakdown',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            21 => 
            array (
                'id' => 22,
                'code' => 'SNBD02',
                'description' => 'Capacity issue',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            22 => 
            array (
                'id' => 23,
                'code' => 'SNBD03',
                'description' => 'Weight issue',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            23 => 
            array (
                'id' => 24,
                'code' => 'PHO-CDAR01',
                'description' => 'Driver accepted damage photograph taken',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            24 => 
            array (
                'id' => 25,
                'code' => 'CDAR02',
                'description' => 'Driver accepted additional freight how many',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            25 => 
            array (
                'id' => 26,
                'code' => 'PHO-CDRR01',
                'description' => 'Driver refused to accept goods photograph taken',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            26 => 
            array (
                'id' => 27,
                'code' => 'CSHR01',
                'description' => 'Driver accepted shortage how many short',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            27 => 
            array (
                'id' => 28,
                'code' => 'CNRR01',
                'description' => 'Not required',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 1,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            28 => 
            array (
                'id' => 29,
                'code' => 'COLLOVER',
                'description' => 'Collection Overage',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            29 => 
            array (
                'id' => 30,
                'code' => 'LOADOVER',
                'description' => 'Load Overage',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 0,
            ),
            30 => 
            array (
                'id' => 31,
                'code' => 'LOADSHORT',
                'description' => 'Load Shortage',
                'resend' => 1,
                'resend_same_day' => 1,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 0,
            ),
            31 => 
            array (
                'id' => 32,
                'code' => 'PHO-DDAR01',
                'description' => 'Customer accepted damage goods',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            32 => 
            array (
                'id' => 33,
                'code' => 'PHO-DDRR01',
                'description' => 'Customer rejected damaged goods',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            33 => 
            array (
                'id' => 34,
                'code' => 'DSHR01',
                'description' => 'How many short and description of goods',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            34 => 
            array (
                'id' => 35,
                'code' => 'DNRR01',
                'description' => 'Did not order',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            35 => 
            array (
                'id' => 36,
                'code' => 'UNLODSHORT',
                'description' => 'Unload Shortage',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 0,
                'notify_department' => 0,
            ),
            36 => 
            array (
                'id' => 65,
                'code' => 'SABD04',
                'description' => 'Authorised to leave',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            37 => 
            array (
                'id' => 66,
                'code' => 'SABC05',
                'description' => 'Nothing to collect',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            38 => 
            array (
                'id' => 67,
                'code' => 'SABD10',
                'description' => 'Goods delivered',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 0,
                'notify_department' => 1,
            ),
            39 => 
            array (
                'id' => 68,
                'code' => 'SABD07',
                'description' => 'Carded',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            40 => 
            array (
                'id' => 69,
                'code' => 'JNBC02',
                'description' => 'Nothing to collect',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 1,
            ),
            41 => 
            array (
                'id' => 70,
                'code' => 'SABD08',
                'description' => 'Signed for damaged',
                'resend' => 0,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 0,
                'add_tracking_event' => 1,
                'notify_department' => 0,
            ),
            42 => 
            array (
                'id' => 71,
                'code' => 'COLLSHORT',
                'description' => 'Collection Shortage',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 0,
            ),
            43 => 
            array (
                'id' => 72,
                'code' => 'DELSHORT',
                'description' => 'Delivery Shortage',
                'resend' => 1,
                'resend_same_day' => 0,
                'hold' => 0,
                'no_collection' => 1,
                'add_tracking_event' => 1,
                'notify_department' => 0,
            ),
        ));
        
        
    }
}