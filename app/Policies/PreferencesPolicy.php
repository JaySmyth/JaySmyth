<?php

namespace App\Policies;

use App\Models\Preference;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreferencesPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure relation.
     *
     * @return bool
     */
    public function before(User $user, Preference $preference)
    {
        if (! $user->relatedTo($preference)) {
            return false;
        }
    }
}
