<?php

namespace App\Policies;

use App\Models\Models\Commodity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommodityPolicy
{
    use HandlesAuthorization;

    /**
     * Show policy.
     *
     * @return bool
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
     * @return bool
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
     * @return bool
     */
    public function delete(User $user, Commodity $commodity)
    {
        if ($user->relatedTo($commodity)) {
            return true;
        }
    }
}
