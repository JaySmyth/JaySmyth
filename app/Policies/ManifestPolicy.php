<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManifestPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return boolean
     */
    public function before(User $user)
    {
        if (!$user->hasIfsRole()) {
            return false;
        }
    }

    /**
     * View policy.
     * 
     * @return boolean
     */
    public function view(User $user)
    {
        if ($user->hasPermission('view_manifest')) {
            return true;
        }
    }

    /**
     * Add shipment to manifest policy.
     * 
     * @return boolean
     */
    public function addShipment(User $user)
    {
        if ($user->hasPermission('add_to_manifest')) {
            return true;
        }
    }

}
