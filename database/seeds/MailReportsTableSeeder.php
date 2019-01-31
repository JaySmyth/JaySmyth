<?php

use Illuminate\Database\Seeder;

class MailReportsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mail_reports')->insert(array(
            array('name' => 'Delivery Exception', 'enabled' => 1),
            array('name' => 'POD', 'enabled' => 1)
        ));
    }

}
