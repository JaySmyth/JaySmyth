<?php

namespace App\Providers;

use App\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Shipment::class => \App\Policies\ShipmentPolicy::class,
        \App\Company::class => \App\Policies\CompanyPolicy::class,
        \App\User::class => \App\Policies\UserPolicy::class,
        \App\Role::class => \App\Policies\RolePolicy::class,
        \App\Address::class => \App\Policies\AddressPolicy::class,
        \App\Commodity::class => \App\Policies\CommodityPolicy::class,
        \App\Manifest::class => \App\Policies\ManifestPolicy::class,
        \App\ManifestProfile::class => \App\Policies\ManifestProfilePolicy::class,
        \App\Service::class => \App\Policies\ServicePolicy::class,
        \App\Report::class => \App\Policies\ReportPolicy::class,
        \App\Preferences::class => \App\Policies\PreferencesPolicy::class,
        \App\CustomsEntry::class => \App\Policies\CustomsEntryPolicy::class,
        \App\FuelSurcharge::class => \App\Policies\FuelSurchargePolicy::class,
        \App\InvoiceRun::class => \App\Policies\InvoiceRunPolicy::class,
        \App\PurchaseInvoice::class => \App\Policies\PurchaseInvoicePolicy::class,
        \App\Report::class => \App\Policies\ReportPolicy::class,
        \App\SeaFreightShipment::class => \App\Policies\SeaFreightShipmentPolicy::class,
        \App\Currency::class => \App\Policies\CurrencyPolicy::class,
        \App\Driver::class => \App\Policies\DriverPolicy::class,
        \App\Vehicle::class => \App\Policies\VehiclePolicy::class,
        \App\TransportJob::class => \App\Policies\TransportJobPolicy::class,
        \App\DriverManifest::class => \App\Policies\DriverManifestPolicy::class,
        \App\TransportAddress::class => \App\Policies\TransportAddressPolicy::class,
        \App\CarrierChargeCode::class => \App\Policies\CarrierChargeCodePolicy::class,
        \App\RateSurcharge::class => \App\Policies\RateSurchargePolicy::class,
        \App\Surcharge::class => \App\Policies\SurchargePolicy::class,
        \App\Postcode::class => \App\Policies\PostcodePolicy::class,
        \App\Quotation::class => \App\Policies\QuotationPolicy::class,
        \App\Log::class => \App\Policies\LogPolicy::class,
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

        // Dynamically register permissions with Laravel's Gate.
        foreach ($this->getPermissions() as $permission) {

            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }

        // Grant all abilities to IFS Admin user
        Gate::before(function($user) {
            if ($user->hasRole('ifsa'))
                return true;
        });
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
