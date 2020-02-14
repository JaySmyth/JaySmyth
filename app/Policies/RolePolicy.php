<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
    public function viewAny(User $user)
    {
        if ($user->hasPermission('view_role')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function permissions(User $user)
    {
        if ($user->hasPermission('view_role')) {
            return true;
        }
    }

    /**
     * Set permissions policy. Only allowed to IFSA users.
     *
     * @return bool
     */
    public function setPermissions(User $user)
    {
        if ($user->hasRole('ifsa')) {
            return true;
        }
    }

    /**
     * Get roles policy.
     *
     * @return bool
     */
    public function getRoles(User $user)
    {
        if ($user->hasIfsRole()) {
            return true;
        }
    }
}
