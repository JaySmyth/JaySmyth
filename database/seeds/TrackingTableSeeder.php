<?php

use Illuminate\Database\Seeder;

class TrackingTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $data[] = ["id" => 1,"message" => "Shipping Label Created","status" => "pre_transit","status_detail" => null,"datetime" => "2019-06-05 15 => 24 => 57","local_datetime" => "2019-06-05 15 => 24 => 57","carrier" => "FedEx","city" => "Carrickfergus","state" => "County Antrim","country_code" => "GB","postcode" => "BT38 8GX","tracker_id" => "trk_uX8gcE9SILpCLlekfMfyUdg5Qv","source" => "ifs","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => null,"local_estimated_delivery_date" => null,"user_id" => 395,"shipment_id" => 1];
        $data[] = ["id" => 2,"message" => "Package 1 received","status" => "received","status_detail" => null,"datetime" => "2019-06-05 16 => 35 => 46","local_datetime" => "2019-06-05 16 => 35 => 46","carrier" => "FedEx","city" => "Antrim","state" => "County Antrim","country_code" => "GB","postcode" => "BT41 4QE","tracker_id" => "trk_Tkf0L9lnesDAQMpMCdQRcyg2wj","source" => "ifs","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => null,"local_estimated_delivery_date" => null,"user_id" => 0,"shipment_id" => 1];
        $data[] = ["id" => 3,"message" => "Package 1 loaded to route","status" => "received","status_detail" => null,"datetime" => "2019-06-05 16 => 35 => 46","local_datetime" => "2019-06-05 16 => 35 => 46","carrier" => "FedEx","city" => "Antrim","state" => "County Antrim","country_code" => "GB","postcode" => "BT41 4QE","tracker_id" => "trk_3jCax1MLizPP8i7ThTGTxTlMLK","source" => "ifs","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => null,"local_estimated_delivery_date" => null,"user_id" => 0,"shipment_id" => 1];
        $data[] = ["id" => 4,"message" => "Departed FedEx location","status" => "in_transit","status_detail" => "departed_facility","datetime" => "2019-06-06 11 => 55 => 00","local_datetime" => "2019-06-06 11 => 55 => 00","carrier" => "FedEx","city" => "NEWCASTLE GB","state" => null,"country_code" => "GB","postcode" => null,"tracker_id" => "trk_15241bf9b7f64dae82bcac1ee18d734f","source" => "easypost","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => "2019-06-06 11 => 55 => 00","local_estimated_delivery_date" => "2019-06-07 17 => 00 => 00","user_id" => 0,"shipment_id" => 1];
        $data[] = ["id" => 5,"message" => "At local FedEx facility","status" => "in_transit","status_detail" => "received_at_destination_facility","datetime" => "2019-06-07 04 => 29 => 00","local_datetime" => "2019-06-07 04 => 29 => 00","carrier" => "FedEx","city" => "WOODBURY GB","state" => null,"country_code" => "GB","postcode" => null,"tracker_id" => "trk_15241bf9b7f64dae82bcac1ee18d734f","source" => "easypost","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => "2019-06-07 04 => 29 => 00","local_estimated_delivery_date" => "2019-06-07 17 => 00 => 00","user_id" => 0,"shipment_id" => 1];
        $data[] = ["id" => 6,"message" => "On FedEx vehicle for delivery","status" => "out_for_delivery","status_detail" => "out_for_delivery","datetime" => "2019-06-07 06 => 30 => 00","local_datetime" => "2019-06-07 06 => 30 => 00","carrier" => "FedEx","city" => "WOODBURY GB","state" => null,"country_code" => "GB","postcode" => null,"tracker_id" => "trk_15241bf9b7f64dae82bcac1ee18d734f","source" => "easypost","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => "2019-06-07 06 => 30 => 00","local_estimated_delivery_date" => "2019-06-07 17 => 00 => 00","user_id" => 0,"shipment_id" => 1];
        $data[] = ["id" => 7,"message" => "Delivered","status" => "delivered","status_detail" => "arrived_at_destination","datetime" => "2019-06-07 09 => 32 => 00","local_datetime" => "2019-06-07 09 => 32 => 00","carrier" => "FedEx","city" => "BARNSTAPLE GB","state" => null,"country_code" => "GB","postcode" => null,"tracker_id" => "trk_15241bf9b7f64dae82bcac1ee18d734f","source" => "easypost","exception_sent" => "0","tracking_sent" => "0","estimated_delivery_date" => "2019-06-07 09 => 32 => 00","local_estimated_delivery_date" => "2019-06-07 17 => 00 => 00","user_id" => 0,"shipment_id" => 1];

        \DB::table('tracking')->delete();

        \DB::table('tracking')->insert($data);
    }
}
