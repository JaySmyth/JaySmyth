<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode('[{"id":1,"name":"Fred Bloggs","email":"fbloggs@antrim.ifsgroup.com","telephone":"02894464211","password":"$2y$10$N2LYO3FDUt6pysadQAfqXeZa.FUlOQ7iC0ktV3U6bEjv9xsWfGzza","label_copies":0,"remember_token":"Ho49WW6NLTnuz9U690Ov3rrObqcUkOFRq722qA9dOqIcoXxmydQiL7REWZMg","api_token":"46f01fd0cb61d6e072983683bdef4c710b8b9b41","enabled":"1","show_search_bar":"1","localisation_id":1,"print_format_id":1,"customer_label":"0","driver_label":"0","browser":"Firefox 74.0","platform":"Windows","screen_resolution":"1536x864","last_login":"2020-04-10 08:49:37","created_at":"2016-11-30 10:07:45","updated_at":"2020-04-10 08:49:37"},{"id":2,"name":"John Smith","email":"jsmith@antrim.ifsgroup.com","telephone":"02894464211","password":"$2y$10$eiPfOufcsb5EE9yAyaKjL.8EaPo6emL6FHuzKG8pZHt9tYxifVKEq","label_copies":0,"remember_token":"PRQan2isQN6T2txGT8qtuYLmJLCO9hYpLdsg3klBvBcLERI796ZGtw5EOhBo","api_token":"91486d75329e60f28ef8742c66918dff4d65c1f3","enabled":"1","show_search_bar":"1","localisation_id":1,"print_format_id":3,"customer_label":"0","driver_label":"0","browser":"Firefox 74.0","platform":"Windows","screen_resolution":"1920x1080","last_login":"2020-03-25 11:19:06","created_at":"2016-11-30 10:07:44","updated_at":"2020-03-25 11:19:06"},{"id":3,"name":"Alice Jones","email":"ajones@crossbows.com","telephone":"02838315161","password":"$2y$10$EVCzNUJ72aE8M/vhkzc5cO/QgYdrHVlhO6EIqukrhDY8AOV5HAKEe","label_copies":0,"remember_token":"50Bg6jf3nK10NyKOBMY0J2WkDx37KbYIE6XS8mMtSnbSvIOC6G46h7hIMtLz","api_token":"b3b0c6ea8a0e3a594dfa73959b312a192c5c9eba","enabled":"1","show_search_bar":"1","localisation_id":1,"print_format_id":1,"customer_label":"0","driver_label":"0","browser":"Chrome 80.0.3987.163","platform":"Windows","screen_resolution":"1920x1080","last_login":"2020-04-09 10:43:01","created_at":"2016-11-30 10:08:09","updated_at":"2020-04-09 10:43:01"}]', true);

        \DB::table('users')->delete();

        \DB::table('users')->insert($data);
    }
}
