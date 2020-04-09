<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
    public function viewAny(User $user)
    {
        if ($user->hasPermission('view_company')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user, Company $company)
    {
        if ($user->hasPermission('view_company') && $user->relatedTo($company)) {
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
        if ($user->hasPermission('create_company')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user, Company $company)
    {
        if ($user->hasPermission('update_company') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return bool
     */
    public function delete(User $user, Company $company)
    {
        if ($user->hasPermission('delete_company') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Status policy.
     *
     * @return bool
     */
    public function status(User $user, Company $company)
    {
        if ($user->hasPermission('change_company_status') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Services policy.
     *
     * @return bool
     */
    public function services(User $user, Company $company)
    {
        if ($user->hasPermission('set_company_services') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * View Rate policy.
     *
     * @return bool
     */
    public function viewCompanyRates(User $user, Company $company)
    {
        if ($user->hasPermission('view_company_rates') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Edit Rate policy.
     *
     * @return bool
     */
    public function setCompanyRates(User $user, Company $company)
    {
        if ($user->hasPermission('set_company_rates') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Destroy Rate policy.
     *
     * @return bool
     */
    public function deleteCompanyRates(User $user, Company $company)
    {
        if ($user->hasPermission('delete_company_rates') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Edit Collection Settings policy.
     *
     * @return bool
     */
    public function collectionSettings(User $user, Company $company)
    {
        if ($user->hasPermission('set_collection_settings') && $user->relatedTo($company)) {
            return true;
        }
    }

    /**
     * Download policy.
     *
     * @return bool
     */
    public function download(User $user)
    {
        if ($user->hasPermission('download_companies')) {
            return true;
        }
    }
}
