<?php

namespace App\Policies;

use App\User;
use App\Commodity;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommodityPolicy
{
    use HandlesAuthorization;

    /**
     * Show policy.
     *
     * @return boolean
     */
    public function view(User $user, Commodity $commodity)
    {
        if ($user->relatedTo($commodity)) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return boolean
     */
    public function update(User $user, Commodity $commodity)
    {
        if ($user->relatedTo($commodity)) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return boolean
     */
    public function delete(User $user, Commodity $commodity)
    {
        if ($user->relatedTo($commodity)) {
            return true;
        }
    }
}
