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
        DB::table('problem_events')->insert([
            ['event' => 'Refused By Consignee', 'relevance' => 's'],
            ['event' => 'Service Not Available In Pcode', 'relevance' => 's'],
            ['event' => 'Delivery Attempted-Card Left', 'relevance' => 's'],
            ['event' => 'Book In Or No Access To Site', 'relevance' => 's'],
            ['event' => 'Hub Mis-Sort', 'relevance' => 's'],
            ['event' => 'Address Query', 'relevance' => 's'],
            ['event' => 'Goods Not Received At Del Depot', 'relevance' => 's'],
        ]);
    }
}
