<?php

namespace App\Policies;

use App\User;
use App\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{

    use HandlesAuthorization;

    /**
     * Show policy.
     *
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function delete(User $user, Address $address)
    {
        if ($user->relatedTo($address)) {
            return true;
        }
    }

}
