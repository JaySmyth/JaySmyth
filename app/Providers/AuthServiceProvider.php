<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Shipment::class => \App\Policies\ShipmentPolicy::class,
        \App\Models\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Models\Address::class => \App\Policies\AddressPolicy::class,
        \App\Models\Models\Commodity::class => \App\Policies\CommodityPolicy::class,
        \App\Models\Models\Manifest::class => \App\Policies\ManifestPolicy::class,
        \App\Models\Models\ManifestProfile::class => \App\Policies\ManifestProfilePolicy::class,
        \App\Models\Service::class => \App\Policies\ServicePolicy::class,
        \App\Models\Report::class => \App\Policies\ReportPolicy::class,
        \App\Models\Preferences::class => \App\Policies\PreferencesPolicy::class,
        \App\Models\Models\CustomsEntry::class => \App\Policies\CustomsEntryPolicy::class,
        \App\Models\Models\FuelSurcharge::class => \App\Policies\FuelSurchargePolicy::class,
        \App\Models\Models\InvoiceRun::class => \App\Policies\InvoiceRunPolicy::class,
        \App\Models\PurchaseInvoice::class => \App\Policies\PurchaseInvoicePolicy::class,
        \App\Models\Report::class => \App\Policies\ReportPolicy::class,
        \App\Models\SeaFreightShipment::class => \App\Policies\SeaFreightShipmentPolicy::class,
        \App\Models\Models\Currency::class => \App\Policies\CurrencyPolicy::class,
        \App\Models\Models\Driver::class => \App\Policies\DriverPolicy::class,
        \App\Models\Vehicle::class => \App\Policies\VehiclePolicy::class,
        \App\Models\TransportJob::class => \App\Policies\TransportJobPolicy::class,
        \App\Models\Models\DriverManifest::class => \App\Policies\DriverManifestPolicy::class,
        \App\Models\TransportAddress::class => \App\Policies\TransportAddressPolicy::class,
        \App\Models\Models\CarrierChargeCode::class => \App\Policies\CarrierChargeCodePolicy::class,
        \App\Models\RateSurcharge::class => \App\Policies\RateSurchargePolicy::class,
        \App\Models\Surcharge::class => \App\Policies\SurchargePolicy::class,
        \App\Models\Postcode::class => \App\Policies\PostcodePolicy::class,
        \App\Models\Quotation::class => \App\Policies\QuotationPolicy::class,
        \App\Models\Models\Log::class => \App\Policies\LogPolicy::class,
        \App\Models\SeaFreightTracking::class => \App\Policies\SeaFreightTrackingPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
        // Dynamically register permissions with Laravel's Gate.
        foreach ($this->getPermissions() as $permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

        // Grant all abilities to IFS Admin user
        Gate::before(function ($user) {
            if ($user->hasRole('ifsa')) {
                return true;
            }
        });
        */
    }

    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
