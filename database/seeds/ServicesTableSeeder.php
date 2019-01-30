<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_string = '[{"id":1,"code":"ni24","name":"IFS NI Deliveries 24Hr","carrier_code":"ni24","carrier_name":"IFS NI Deliveries 24Hr","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"GB","sender_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","recipient_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":1,"depot_id":1,"cost_rate_id":1,"sales_rate_id":500},{"id":2,"code":"ni48","name":"IFS NI Deliveries 48Hr","carrier_code":"ni48","carrier_name":"IFS NI Deliveries 48Hr","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"GB","sender_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","recipient_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":1,"depot_id":1,"cost_rate_id":2,"sales_rate_id":500},{"id":3,"code":"ie48","name":"IFS IE Deliveries 48Hr","carrier_code":"ie48","carrier_name":"IFS IE Deliveries 48Hr","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"IE","sender_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","recipient_postcode_regex":"","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":1,"depot_id":1,"cost_rate_id":5,"sales_rate_id":500},{"id":4,"code":"air","name":"IFS Airfreight","carrier_code":"air","carrier_name":"IFS Airfreight","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"","sender_postcode_regex":"","recipient_postcode_regex":"/^(?!BT)/","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":2,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":5,"code":"road","name":"IFS Roadfreight","carrier_code":"road","carrier_name":"IFS Roadfreight","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":3,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":6,"code":"sea","name":"IFS Seafreight","carrier_code":"sea","carrier_name":"IFS Seafreight","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"!GB,IE","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":4,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":7,"code":"usg","name":"Courier Canada (Fedex)","carrier_code":"92","carrier_name":"FedEx Ground (US/CA Only)","account":"205691588","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"US,CA","recipient_country_codes":"US,CA","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/[0-9]{9}/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"0","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":2,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":8,"code":"ipf","name":"Courier Express Freight (EU)","carrier_code":"70","carrier_name":"FedEx Freight","account":"205691588","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"!GB,IE","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/[0-9]{9}/","min_weight":60,"max_weight":9999,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":2,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":9,"code":"ie","name":"Courier Economy (Fedex)","carrier_code":"03","carrier_name":"FedEx International Economy","account":"205691588","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"!GB","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/[0-9]{9}/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"1","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":2,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":10,"code":"ip","name":"IFS Courier Express (Fedex IP)","carrier_code":"01","carrier_name":"FedEx International Priority","account":"205691588","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"!GB","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/[0-9]{9}/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"1","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":2,"mode_id":1,"depot_id":1,"cost_rate_id":30,"sales_rate_id":600},{"id":11,"code":"ip","name":"IFS Courier Express (UPS IP)","carrier_code":"65","carrier_name":"UPS Saver","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"!GB","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":3,"mode_id":1,"depot_id":1,"cost_rate_id":41,"sales_rate_id":600},{"id":12,"code":"std","name":"IFS Courier Standard (UPS - EU Road)","carrier_code":"11","carrier_name":"UPS Standard","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"!GB,IE","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":3,"mode_id":1,"depot_id":1,"cost_rate_id":42,"sales_rate_id":700},{"id":13,"code":"uk48p","name":"Courier UK 48Hr Pallets","carrier_code":"","carrier_name":"IFS","account":"IFS","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"GB","sender_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","recipient_postcode_regex":"/^(?!BT)/","account_number_regex":"/[0-9]{9}/","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":1,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":14,"code":"eu24","name":"IFS Courier EU24Hr (UPS - Road)","carrier_code":"07","carrier_name":"UPS Worldwide Express 24Hr","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"!GB","sender_postcode_regex":"","recipient_postcode_regex":"/^(?!BT)/","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":3,"mode_id":1,"depot_id":1,"cost_rate_id":40,"sales_rate_id":704},{"id":15,"code":"eu+","name":"Courier Express Plus (UPS)","carrier_code":"54","carrier_name":"UPS Worldwide Express Plus","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"!GB","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":3,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":16,"code":"uk24","name":"Courier UK24 (UPS)","carrier_code":"65","carrier_name":"UPS Saver","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"GB","sender_postcode_regex":"","recipient_postcode_regex":"/^(?!BT)/","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":3,"mode_id":1,"depot_id":1,"cost_rate_id":7,"sales_rate_id":710},{"id":17,"code":"upsfrt","name":"Courier Express Freight (UPS)","carrier_code":"E1","carrier_name":"UPS Express Freight","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"!GB","sender_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","recipient_postcode_regex":"","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":60,"max_weight":999,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":0,"mode_id":0,"depot_id":0,"cost_rate_id":0,"sales_rate_id":0},{"id":18,"code":"uk48r","name":"Courier UK48 Returns (UPS)","carrier_code":"11","carrier_name":"UPS Standard","account":"922E2A","volumetric_divisor":5000,"parameters":null,"default":"0","sender_country_codes":"GB","recipient_country_codes":"GB","sender_postcode_regex":"/^(?!BT)/","recipient_postcode_regex":"/^BT[A-Z0-9 ]{3,6}$/","account_number_regex":"/^[0-9A-Z]{6}$/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":3,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":19,"code":"uk48","name":"Courier UK48 (Fedex)","carrier_code":"25","carrier_name":"FedEx Economy","account":"205691588","volumetric_divisor":5000,"parameters":null,"default":"1","sender_country_codes":"GB","recipient_country_codes":"GB","sender_postcode_regex":"","recipient_postcode_regex":"/^(?!BT)/","account_number_regex":"/[0-9]{9}/","min_weight":0,"max_weight":70,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"!pal,hpa","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"0","9am":"0","1030am":"0","12pm":"0","carrier_id":2,"mode_id":1,"depot_id":1,"cost_rate_id":9,"sales_rate_id":500},{"id":20,"code":"RM48","name":"Royal Mail","carrier_code":"??","carrier_name":"Royal Mail","account":"","volumetric_divisor":0,"parameters":null,"default":"0","sender_country_codes":"","recipient_country_codes":"","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":6,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0},{"id":21,"code":"tn","name":"TNT","carrier_code":"??","carrier_name":"TNT","account":"","volumetric_divisor":0,"parameters":null,"default":"0","sender_country_codes":"","recipient_country_codes":"","sender_postcode_regex":"","recipient_postcode_regex":"","account_number_regex":"","min_weight":0,"max_weight":0,"max_pieces":0,"max_dimension":0,"max_customs_value":0,"packaging_types":"","hazardous":"0","dry_ice":"0","alcohol":"0","broker":"0","doc":"1","nondoc":"1","eu":"1","non_eu":"1","9am":"0","1030am":"0","12pm":"0","carrier_id":4,"mode_id":1,"depot_id":1,"cost_rate_id":0,"sales_rate_id":0}]';

        $service = json_decode($json_string, true);

        // Modify a few records
        DB::table('services')->insert($service);
    }

}
