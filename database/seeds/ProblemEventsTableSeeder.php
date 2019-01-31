<?php

use Illuminate\Database\Seeder;

class ProblemEventsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert some dummy records
        DB::table('problem_events')->insert(array(
            array('event' => 'Refused By Consignee', 'relevance' => 's'),
            array('event' => 'Service Not Available In Pcode', 'relevance' => 's'),
            array('event' => 'Delivery Attempted-Card Left', 'relevance' => 's'),
            array('event' => 'Book In Or No Access To Site', 'relevance' => 's'),
            array('event' => 'Hub Mis-Sort', 'relevance' => 's'),
            array('event' => 'Address Query', 'relevance' => 's'),
            array('event' => 'Goods Not Received At Del Depot', 'relevance' => 's'),
        ));
    }

}
