<?php

namespace App\Policies;

use App\User;
use App\ManifestProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManifestProfilePolicy
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
     * Index policy.
     * 
     * @return boolean
     */
    public function view(User $user)
    {
        if ($user->hasPermission('view_manifest_profile')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return boolean
     */
    public function update(User $user)
    {
        if ($user->hasRole('ifsa')) {
            return true;
        }
    }

    /**
     * Run manifest. 
     *
     * @return boolean
     */
    public function runManifest(User $user, ManifestProfile $manifestProfile)
    {
        if ($user->hasPermission('run_manifest') && $user->hasDepot($manifestProfile->depot_id)) {
            return true;
        }
    }

    /**
     * Bulk hold. 
     *
     * @return boolean
     */
    public function bulkHold(User $user, ManifestProfile $manifestProfile)
    {
        if ($user->hasPermission('run_manifest') && $user->hasDepot($manifestProfile->depot_id)) {
            return true;
        }
    }

}
