<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvalidCommodityDescriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return bool
     */
    public function before(User $user)
    {
        if (! $user->hasIfsRole() || ! $user->hasPermission('create_invalid_commodity_description')) {
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
        if ($user->hasIfsRole() && $user->hasPermission('create_invalid_commodity_description')) {
            return true;
        }
    }
}
