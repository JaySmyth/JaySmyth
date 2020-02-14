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
     * @return bool
     */
    public function before(User $user)
    {
        if (! $user->hasIfsRole()) {
            return false;
        }
    }

    /**
     * View policy.
     *
     * @return bool
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
     * @return bool
     */
    public function addShipment(User $user)
    {
        if ($user->hasPermission('add_to_manifest')) {
            return true;
        }
    }
}
