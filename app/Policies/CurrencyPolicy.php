<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return boolean
     */
    public function before(User $user)
    {
        if (!$user->hasIfsRole() || !$user->hasPermission('currency_admin')) {
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
        if ($user->hasIfsRole() && $user->hasPermission('currency_admin')) {
            return true;
        }
    }

}
