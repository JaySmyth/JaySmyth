<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Company;

class CompanyPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return boolean
     */
    public function before(User $user)
    {
        if (!$user->hasIfsRole()) {
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
        if ($user->hasPermission('view_company')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function download(User $user)
    {
        if ($user->hasPermission('download_companies')) {
            return true;
        }
    }

}
