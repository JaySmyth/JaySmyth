<?php

namespace App\Policies;

use App\Models\CompanyService;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyServicePolicy
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
     * Show policy.
     *
     * @return bool
     */
    public function viewAny(User $user, CompanyService $companyService)
    {
        if (! $user->hasIfsRole()) {
            return false;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user, CompanyService $companyService)
    {
        if (! $user->hasIfsRole()) {
            return false;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user, CompanyService $companyService)
    {
        if ($user->hasPermission('update_service_filters')) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return bool
     */
    public function delete(User $user, CompanyService $companyService)
    {
        return false;

        if ($user->hasPermission('update_service_filters')) {
            return true;
        }
    }
}
