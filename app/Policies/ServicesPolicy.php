<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicesPolicy
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
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user, Service $service)
    {
        if ($user->hasRole('ifsa')) {
            return true;
        }

        return false;
    }
}
