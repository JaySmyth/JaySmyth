<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FuelSurchargePolicy
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
        if ($user->hasPermission('view_fuel_surcharge')) {
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
        if ($user->hasPermission('view_fuel_surcharge')) {
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
        if ($user->hasPermission('create_fuel_surcharge')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user)
    {
        if ($user->hasPermission('create_fuel_surcharge')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function upload(User $user)
    {
        if ($user->hasPermission('create_fuel_surcharge')) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return bool
     */
    public function delete(User $user)
    {
        if ($user->hasPermission('delete_fuel_surcharge')) {
            return true;
        }
    }
}
