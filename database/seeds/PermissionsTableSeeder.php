<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'courier',
                'label' => 'Courier',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'air',
                'label' => 'Air Freight',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'road',
                'label' => 'Road Freight',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'sea',
                'label' => 'Sea Freight',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'create_shipment',
                'label' => 'Create Shipment',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'update_shipment',
                'label' => 'Update Shipment',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'cancel_shipment',
                'label' => 'Cancel Shipment',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'download_shipments',
                'label' => 'Download Shipments',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'view_company',
                'label' => 'View Company',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'create_company',
                'label' => 'Create Company',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'update_company',
                'label' => 'Update Company',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'delete_company',
                'label' => 'Delete Company',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'set_company_services',
                'label' => 'Set Company Services',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'change_company_status',
                'label' => 'Change Company Status',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'view_user',
                'label' => 'View User',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'create_user',
                'label' => 'Create User',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'update_user',
                'label' => 'Update User',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'delete_user',
                'label' => 'Delete User',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'add_company',
                'label' => 'Add Company',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'remove_company',
                'label' => 'Remove User Company',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'reset_password',
                'label' => 'Reset Password',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'view_manifest',
                'label' => 'View Manifest',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'add_to_manifest',
                'label' => 'Add To Manifest',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'view_manifest_profile',
                'label' => 'View Manifest Profile',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'run_manifest',
                'label' => 'Run Manifest',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'view_rate',
                'label' => 'View Rate',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'create_rate',
                'label' => 'Create Rate',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'update_rate',
                'label' => 'Update Rate',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'delete_rate',
                'label' => 'Delete Rate',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'view_collection',
                'label' => 'View Collection',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'create_collection',
                'label' => 'Create Collection',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'update_collection',
                'label' => 'Update Collection',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'delete_collection',
                'label' => 'Delete Collection',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'view_purchase_invoice',
                'label' => 'View Purchase Invoice',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'compare_purchase_invoice',
                'label' => 'Compare Purchase Invoice Costs',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'purchase_invoice_admin',
                'label' => 'Perform Purchase Invoice Admin Tasks',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'view_reports',
                'label' => 'View Reports',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'view_dim_report',
                'label' => 'View DIM Report',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'view_fedex_customs_report',
                'label' => 'View FedEx Customs Report',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'view_non_shippers_report',
                'label' => 'View Non Shippers Report',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'view_scanning_report',
                'label' => 'View Scanning Report',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'view_shippers_report',
                'label' => 'View Shippers Report',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'view_role',
                'label' => 'View Role Permissions',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'manage_addresses',
                'label' => 'Manage Addresses',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'view_customs_entry',
                'label' => 'View Customs Entry',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'create_customs_entry',
                'label' => 'Create Customs Entry',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'delete_customs_entry',
                'label' => 'Delete Customs Entry',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'download_customs_entry',
                'label' => 'Download Customs Entries',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'create_cpc',
                'label' => 'Create Customs CPC',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'view_fuel_surcharge',
                'label' => 'View Fuel Surcharge',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'create_fuel_surcharge',
                'label' => 'Create Fuel Surcharge',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'delete_fuel_surcharge',
                'label' => 'Delete Fuel Surcharge',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'manage_commodities',
                'label' => 'Manage Commodities',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'currency_admin',
                'label' => 'Perform Currency Admin Tasks',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'view_active_shipments_report',
                'label' => 'View Active Shipments Report',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'view_pod_report',
                'label' => 'View POD Report',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'courier_billing',
                'label' => 'Set Courier Billing Options',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'courier_broker',
                'label' => 'Set Courier Broker Options',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'sales_invoice_admin',
                'label' => 'Sales Invoice Admin',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'accounts_menu',
                'label' => 'Display Accounts Menu',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'transport_menu',
                'label' => 'Display Transport Menu',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'admin_menu',
                'label' => 'Display Admin Menu',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'create_invoice_run',
            'label' => 'Create Invoice Run (sales)',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'pod_shipments',
                'label' => 'POD Shipments',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'update_dims',
                'label' => 'Update DIMS',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'view_vehicle',
                'label' => 'View Vehicle',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'view_driver',
                'label' => 'View Driver',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'view_transport_job',
                'label' => 'View Transport Job',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'create_transport_job',
                'label' => 'Create Transport Job',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'manifest_transport_jobs',
            'label' => 'Manifest Transport Jobs (allocate to driver)',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'close_transport_job',
                'label' => 'Close / POD Transport Job',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'cancel_transport_job',
                'label' => 'Cancel Transport Job',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'unmanifest_transport_job',
            'label' => 'Unmanifest Transport Job (remove from driver manifest)',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'view_driver_manifest',
                'label' => 'View Driver Manifest',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'create_driver_manifest',
                'label' => 'Create Driver Manifest',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'close_driver_manifest',
                'label' => 'Close Driver Manifest',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'open_driver_manifest',
                'label' => 'Open Driver Manifest',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'edit_transport_job',
                'label' => 'Edit Transport Job',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'create_driver',
                'label' => 'Create Driver',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'view_company_rates',
                'label' => 'View Company Rates',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'set_company_rates',
                'label' => 'Set Company Rates',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'delete_company_rates',
                'label' => 'Delete Company Rates',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'set_purchase_invoice_flags',
                'label' => 'Set Flags on Purchase Invoices',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'view_invoice_run',
                'label' => 'View Invoice Run',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'view_unknown_jobs_report',
                'label' => 'View Unknown Jobs Report',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'view_unmanifested_jobs',
                'label' => 'View Unmanifested Transport Jobs',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'view_daily_stats_report',
                'label' => 'View Daily Stats Report',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'view_exceptions_report',
                'label' => 'View Exceptions Report',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'view_carrier_charge_codes',
                'label' => 'View Carrier Charge Codes',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'view_fedex_international_available_report',
                'label' => 'View FedEx International Available Report',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'create_rate_surcharge',
                'label' => 'Maintain Rate Surcharges',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'view_carrier_scans_report',
                'label' => 'View Carrier Scans Report',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'view_purchase_invoice_lines_report',
                'label' => 'View Purchase Invoice Lines Report',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'view_pre_transit_report',
                'label' => 'View Pre-Transit Shipment Report',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'view_hazardous_report',
                'label' => 'View Hazardous / Dry Ice Shipments Report',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'set_collection_settings',
                'label' => 'Set Company Collection / Delivery Settings',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'create_postcode',
                'label' => 'Postcode administration',
            ),
            97 => 
            array (
                'id' => 99,
                'name' => 'view_shipments_by_carrier_report',
                'label' => 'View Shipments By Carrier Report',
            ),
            98 => 
            array (
                'id' => 100,
                'name' => 'view_quotation',
                'label' => 'View Quotation',
            ),
            99 => 
            array (
                'id' => 101,
                'name' => 'create_quotation',
                'label' => 'Create Quotation',
            ),
            100 => 
            array (
                'id' => 102,
                'name' => 'edit_quotation',
                'label' => 'Edit Quotation',
            ),
            101 => 
            array (
                'id' => 103,
                'name' => 'delete_quotation',
                'label' => 'Delete Quotation',
            ),
            102 => 
            array (
                'id' => 105,
                'name' => 'view_logs',
                'label' => 'View All Logs',
            ),
            103 => 
            array (
                'id' => 106,
                'name' => 'view_surcharges',
                'label' => 'View Surcharges',
            ),
            104 => 
            array (
                'id' => 107,
                'name' => 'view_collection_settings_report',
                'label' => 'View Collection Settings Report',
            ),
            105 => 
            array (
                'id' => 108,
                'name' => 'view_scanning_kpis_report',
                'label' => 'View Scanning KPIs Report',
            ),
            106 => 
            array (
                'id' => 109,
                'name' => 'view_margins_report',
                'label' => 'View Margins Report',
            ),
        ));
        
        
    }
}