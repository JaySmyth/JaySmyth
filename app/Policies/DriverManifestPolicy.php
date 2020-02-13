<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverManifestPolicy
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
     * Index policy.
     *
     * @return bool
     */
    public function index(User $user)
    {
        if ($user->hasPermission('view_driver_manifest')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->hasPermission('view_driver_manifest')) {
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
        if ($user->hasPermission('create_driver_manifest')) {
            return true;
        }
    }

    /**
     * Close manifest.
     *
     * @return bool
     */
    public function close(User $user)
    {
        if ($user->hasPermission('close_driver_manifest')) {
            return true;
        }
    }

    /**
     * Open manifest.
     *
     * @return bool
     */
    public function open(User $user)
    {
        if ($user->hasPermission('open_driver_manifest')) {
            return true;
        }
    }
}
