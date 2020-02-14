<?php

namespace App\Policies;

use App\Shipment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShipmentPolicy
{
    use HandlesAuthorization;

    /**
     * Index policy.
     *
     * @return bool
     */
    public function index(User $user)
    {
        if ($user->hasPermission('create_shipment')) {
            return true;
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
    public function view(User $user, Shipment $shipment)
    {
        if ($user->hasPermission($shipment->mode->name) && $user->relatedTo($shipment)) {
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
    public function update(User $user, Shipment $shipment)
    {
        if ($shipment->company_id) {
            if ($user->hasPermission('update_shipment') && $user->hasPermission($shipment->mode->name) && $user->relatedTo($shipment)) {
                return true;
            }
        }

        if ($user->hasPermission('update_shipment')) {
            return true;
        }
    }

    /**
     * Cancel policy.
     *
     * @return bool
     */
    public function cancel(User $user, Shipment $shipment)
    {
        if ($user->hasPermission('cancel_shipment') && $user->hasPermission($shipment->mode->name) && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Hold policy.
     *
     * @return bool
     */
    public function hold(User $user, Shipment $shipment)
    {
        if ($user->hasIfsRole() && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Receive policy.
     *
     * @return bool
     */
    public function receive(User $user, Shipment $shipment)
    {
        if ($user->hasIfsRole() && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * Undo cancel policy.
     *
     * @return bool
     */
    public function undoCancel(User $user, Shipment $shipment)
    {
        if ($user->hasIfsRole() && $user->relatedTo($shipment)) {
            return true;
        }
    }

    /**
     * DIMS policy.
     *
     * @return bool
     */
    public function dims(User $user)
    {
        if ($user->hasIfsRole()) {
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
     * Upload policy.
     *
     * @return bool
     */
    public function upload(User $user)
    {
        if ($user->canUploadShipments()) {
            return true;
        }
    }

    /**
     * Raw data policy.
     *
     * @return bool
     */
    public function rawData(User $user)
    {
        if ($user->hasRole('ifsa')) {
            return true;
        }
    }

    /**
     * Test emails policy.
     *
     * @return bool
     */
    public function sendTestEmail(User $user)
    {
        if ($user->hasRole('ifsa')) {
            return true;
        }
    }
}
