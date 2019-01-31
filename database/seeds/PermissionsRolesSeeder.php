<?php

use Illuminate\Database\Seeder;

class PermissionsRolesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->insert([
            // Customer Roles
            ['name' => 'cust', 'label' => 'Customer', 'description' => 'Standard user access for customers.', 'primary' => 1, 'ifs_only' => 0],
            ['name' => 'cusa', 'label' => 'Customer Admin', 'description' => 'Admin access for customers. Allows for user admin and additional reporting.', 'primary' => 1, 'ifs_only' => 0],
            ['name' => 'cudv', 'label' => 'Customer Duty & VAT only', 'description' => 'Allows a user to view customs entries ONLY, for duty and VAT reporting purposes.', 'primary' => 1, 'ifs_only' => 0],
            // IFS Roles
            ['name' => 'ifsu', 'label' => 'IFS User', 'description' => 'Standard IFS user. Should be applied to all NON managerial employees.', 'primary' => 1, 'ifs_only' => 1],
            ['name' => 'ifsm', 'label' => 'IFS Manager', 'description' => 'For IFS department managers. Enables company admin and additional reporting.', 'primary' => 1, 'ifs_only' => 1],
            ['name' => 'ifsf', 'label' => 'IFS Accounts', 'description' => 'For IFS staff within the accounts department only. Allows access to purchase invoices and specific reporting.', 'primary' => 1, 'ifs_only' => 1],
            ['name' => 'ifss', 'label' => 'IFS Sales', 'description' => 'For IFS salespersons. Allows access to their specific customers and sales reports.', 'primary' => 1, 'ifs_only' => 1],
            ['name' => 'ifsc', 'label' => 'IFS Customs', 'description' => 'For IFS customs staff. Allows the creation of customs entries only, for Duty and VAT reporting purposes.', 'primary' => 1, 'ifs_only' => 1],
            ['name' => 'ifsa', 'label' => 'IFS Admin', 'description' => 'For complete access to all areas of the application and all depots. Should be restricted to IT only.', 'primary' => 1, 'ifs_only' => 1],
            // Shipper Roles            
            ['name' => 'courier', 'label' => 'Courier Shipper', 'description' => 'Allows a user to generate a Courier shipment.', 'primary' => 0, 'ifs_only' => 0],
            ['name' => 'air', 'label' => 'Air Shipper', 'description' => 'Allows a user to generate an Air Freight shipment.', 'primary' => 0, 'ifs_only' => 0],
            ['name' => 'road', 'label' => 'Road Shipper', 'description' => 'Allows a user to generate a Road Freight shipment.', 'primary' => 0, 'ifs_only' => 0],
            ['name' => 'sea', 'label' => 'Sea Shipper', 'description' => 'Allows a user to generate a Sea Freight shipment.', 'primary' => 0, 'ifs_only' => 0],
        ]);


        DB::table('permissions')->insert([
            /*
              |--------------------------------------------------------------------------
              | Mode permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'courier', 'label' => 'Courier'],
            ['name' => 'air', 'label' => 'Air Freight'],
            ['name' => 'road', 'label' => 'Road Freight'],
            ['name' => 'sea', 'label' => 'Sea Freight'],
            /*
              |--------------------------------------------------------------------------
              | Shipment permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'create_shipment', 'label' => 'Create Shipment'],
            ['name' => 'update_shipment', 'label' => 'Update Shipment'],
            ['name' => 'cancel_shipment', 'label' => 'Cancel Shipment'],
            ['name' => 'download_shipment', 'label' => 'Download Shipments'],
            /*
              |--------------------------------------------------------------------------
              | Company permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_company', 'label' => 'View Company'],
            ['name' => 'create_company', 'label' => 'Create Company'],
            ['name' => 'update_company', 'label' => 'Update Company'],
            ['name' => 'delete_company', 'label' => 'Delete Company'],
            ['name' => 'set_company_services', 'label' => 'Set Company Services'],
            ['name' => 'change_company_status', 'label' => 'Change Company Status'],
            /*
              |--------------------------------------------------------------------------
              | User permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_user', 'label' => 'View User'],
            ['name' => 'create_user', 'label' => 'Create User'],
            ['name' => 'update_user', 'label' => 'Update User'],
            ['name' => 'delete_user', 'label' => 'Delete User'],
            ['name' => 'add_company', 'label' => 'Add Company'],
            ['name' => 'remove_company', 'label' => 'Remove User Company'],
            ['name' => 'reset_password', 'label' => 'Reset Password'],
            /*
              |--------------------------------------------------------------------------
              | Manifest permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_manifest', 'label' => 'View Manifest'],
            ['name' => 'add_to_manifest', 'label' => 'Add To Manifest'],
            /*
              |--------------------------------------------------------------------------
              | Manifest Profiles permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_manifest_profile', 'label' => 'View Manifest Profile'],
            ['name' => 'run_manifest', 'label' => 'Run Manifest'],
            /*
              |--------------------------------------------------------------------------
              | Rates permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_rate', 'label' => 'View Rate'],
            ['name' => 'create_rate', 'label' => 'Create Rate'],
            ['name' => 'update_rate', 'label' => 'Update Rate'],
            ['name' => 'delete_rate', 'label' => 'Delete Rate'],
            /*
              |--------------------------------------------------------------------------
              | Collections permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_collection', 'label' => 'View Collection'],
            ['name' => 'create_collection', 'label' => 'Create Collection'],
            ['name' => 'update_collection', 'label' => 'Update Collection'],
            ['name' => 'delete_collection', 'label' => 'Delete Collection'],
            /*
              |--------------------------------------------------------------------------
              | Purchase invoice permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_purchase_invoice', 'label' => 'View Purchase Invoice'],
            ['name' => 'compare_purchase_invoice', 'label' => 'Compare Purchase Invoice Costs'],
            ['name' => 'purchase_invoice_admin', 'label' => 'Perform Purchase Invoice Admin Tasks'],
            /*
              |--------------------------------------------------------------------------
              | Reports permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_reports', 'label' => 'View Reports'],
            ['name' => 'view_dim_report', 'label' => 'View DIM Report'],
            ['name' => 'view_fedex_customs_report', 'label' => 'View FedEx Customs Report'],
            ['name' => 'view_non_shippers_report', 'label' => 'View Non Shippers Report'],
            ['name' => 'view_scanning_report', 'label' => 'View Scanning Report'],
            ['name' => 'view_shippers_report', 'label' => 'View Shippers Report'],
            /*
              |--------------------------------------------------------------------------
              | Roles permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_role', 'label' => 'View Role Permissions'],
            /*
              |--------------------------------------------------------------------------
              | Address permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'manage_addresses', 'label' => 'Manage Addresses'],
            /*
              |--------------------------------------------------------------------------
              | Customs Entries permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_customs_entry', 'label' => 'View Customs Entry'],
            ['name' => 'create_customs_entry', 'label' => 'Create Customs Entry'],
            ['name' => 'delete_customs_entry', 'label' => 'Delete Customs Entry'],
            ['name' => 'download_customs_entry', 'label' => 'Download Customs Entries'],
            /*
              |--------------------------------------------------------------------------
              | Customs CPC permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'create_cpc', 'label' => 'Create Customs CPC'],
            /*
              |--------------------------------------------------------------------------
              |Fuel Surcharge permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_fuel_surcharge', 'label' => 'View Fuel Surcharge'],
            ['name' => 'create_fuel_surcharge', 'label' => 'Create Fuel Surcharge'],
            ['name' => 'delete_fuel_surcharge', 'label' => 'Delete Fuel Surcharge'],
            /*
              |--------------------------------------------------------------------------
              |Transport permissions
              |--------------------------------------------------------------------------
             */
            ['name' => 'view_driver', 'label' => 'View Driver'],
            ['name' => 'view_vehicle', 'label' => 'View Driver'],
            ['name' => 'view_driver_manifest', 'label' => 'View Driver'],
            ['name' => 'manifest_jobs', 'label' => 'View Driver'],
            ['name' => 'pod_shipments', 'label' => 'POD Shipments'],
        ]);


        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Customer role (cust)
          |--------------------------------------------------------------------------
         */

        $role = \App\Role::whereName('cust')->firstOrFail();
        $role->assignPermission('create_shipment');
        $role->assignPermission('update_shipment');
        $role->assignPermission('cancel_shipment');

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Customer Admin (cusa)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('cusa')->firstOrFail();
        $role->assignPermission('create_shipment');
        $role->assignPermission('update_shipment');
        $role->assignPermission('cancel_shipment');
        $role->assignPermission('view_user');
        $role->assignPermission('create_user');
        $role->assignPermission('update_user');
        $role->assignPermission('reset_password');

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Customer Duty and VAT role (cudv)
          |--------------------------------------------------------------------------
         */

        $role = \App\Role::whereName('cudv')->firstOrFail();
        $role->assignPermission('view_customs_entry');
        $role->assignPermission('download_customs_entry');

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Courier Shipper role (courier)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('courier')->firstOrFail();
        $role->assignPermission('courier');
        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Air Shipper role (air)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('air')->firstOrFail();
        $role->assignPermission('air');
        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Road Shipper role (road)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('road')->firstOrFail();
        $role->assignPermission('road');
        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the Sea Shipper role (sea)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('sea')->firstOrFail();
        $role->assignPermission('sea');
        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the IFS User role (ifsu)
          |--------------------------------------------------------------------------
         */

        // Get an array of shipping modes
        $modes = \App\Mode::all()->pluck('name')->toArray();
        $permissions = \App\Permission::whereNotIn('name', $modes)->get();

        $role = \App\Role::whereName('ifsu')->firstOrFail();

        foreach ($permissions as $permission) {
            $role->assignPermission($permission->name);
        }

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the IFS Managaer role (ifsm)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('ifsm')->firstOrFail();

        foreach ($permissions as $permission) {
            $role->assignPermission($permission->name);
        }

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the IFS Sales role (ifss)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('ifss')->firstOrFail();
        $role->assignPermission('view_reports');

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the IFS Finance/Accounts role (ifsf)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('ifsf')->firstOrFail();
        $role->assignPermission('view_purchase_invoice');
        $role->assignPermission('compare_purchase_invoice');
        $role->assignPermission('purchase_invoice_admin');

        /*
          |--------------------------------------------------------------------------
          | Assign permissions to the IFS Customs role (ifsc)
          |--------------------------------------------------------------------------
         */
        $role = \App\Role::whereName('ifsc')->firstOrFail();
        $role->assignPermission('view_customs_entry');
        $role->assignPermission('create_customs_entry');
        $role->assignPermission('delete_customs_entry');
        $role->assignPermission('download_customs_entry');
    }

}
