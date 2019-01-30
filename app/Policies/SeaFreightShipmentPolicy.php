<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\SeaFreightShipment;

class SeaFreightShipmentPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure all users have sea role
     *
     * @return boolean
     */
    public function before(User $user)
    {
        if (!$user->hasPermission('sea')) {
            return false;
        }
    }

    /**
     * Index policy.
     *
     * @return boolean
     */
    public function index(User $user)
    {
        if ($user->hasPermission('create_shipment')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function addContainer(User $user, SeaFreightShipment $shipment)
    {
        if ($user->hasPermission('create_shipment') && $user->relatedTo($shipment)) {
            return true;
        }
    }

}
