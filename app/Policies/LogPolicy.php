<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
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
    public function index(User $user)
    {
        if ($user->hasPermission('view_logs')) {
            return true;
        }
    }

    /**
     * Get roles policy.
     *
     * @return boolean
     */
    public function getData(User $user)
    {
        if ($user->hasIfsRole()) {
            return true;
        }
    }

}
