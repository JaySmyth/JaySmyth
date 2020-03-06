<?php

namespace App\Policies;

use App\Models\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user, Address $address)
    {
        if ($user->relatedTo($address)) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user, Address $address)
    {
        if ($user->relatedTo($address)) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return bool
     */
    public function delete(User $user, Address $address)
    {
        if ($user->relatedTo($address)) {
            return true;
        }
    }
}
