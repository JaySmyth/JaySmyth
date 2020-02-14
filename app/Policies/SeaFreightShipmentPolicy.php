<?php

namespace App\Policies;

use App\SeaFreightShipment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeaFreightShipmentPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure all users have sea role.
     *
     * @return bool
     */
    public function before(User $user)
    {
        if (! $user->hasPermission('sea')) {
            return false;
        }
    }

    /**
     * Index policy.
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermission('create_shipment')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user, SeaFreightShipment $shipment)
    {
        if ($user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Create policy.
     *
     * @return bool
     */
    public function create(User $user)
    {
        if ($user->hasPermission('create_shipment')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user, SeaFreightShipment $shipment)
    {
        if ($user->hasPermission('update_shipment') && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Cancel policy.
     *
     * @return bool
     */
    public function cancel(User $user, SeaFreightShipment $shipment)
    {
        if ($user->hasPermission('cancel_shipment') && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Receive policy.
     *
     * @return bool
     */
    public function process(User $user, SeaFreightShipment $shipment)
    {
        if ($user->hasIfsRole() && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * POD policy.
     *
     * @return bool
     */
    public function status(User $user, SeaFreightShipment $shipment)
    {
        if ($user->hasIfsRole() && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Download policy.
     *
     * @return bool
     */
    public function download(User $user)
    {
        if ($user->hasPermission('download_shipments')) {
            return true;
        }
    }

    /**
     * Add commodity policy.
     *
     * @return bool
     */
    public function addContainer(User $user, SeaFreightShipment $shipment)
    {
        if ($user->hasPermission('create_shipment') && $user->relatedTo($shipment)) {
            return true;
        }
    }
}
