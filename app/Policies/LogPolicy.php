<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
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
        if ($user->hasPermission('view_logs')) {
            return true;
        }
    }

    /**
     * Get roles policy.
     *
     * @return bool
     */
    public function getData(User $user)
    {
        if ($user->hasIfsRole()) {
            return true;
        }
    }
}
