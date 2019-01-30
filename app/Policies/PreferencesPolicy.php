<?php

namespace App\Policies;

use App\User;
use App\Preference;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreferencesPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure relation.
     *
     * @return boolean
     */
    public function before(User $user, Preference $preference)
    {
        if (!$user->relatedTo($preference)) {
            return false;
        }
    }

}
