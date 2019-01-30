<?php

namespace App;

use Illuminate\Support\Facades\DB;

trait HasRoles
{

    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
                        Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * Update user's roles.
     *
     * @param  array $roles
     * @param  integer $role_id
     * @return mixed
     */
    public function syncRoles($roles, $role_id = null)
    {
        if (is_array($roles) && $role_id) {
            return $this->roles()->sync(array_prepend($roles, $role_id));
        }

        if (is_array($roles) && !$role_id) {
            return $this->roles()->sync($roles);
        }

        if ($role_id) {
            return $this->roles()->sync([$role_id]);
        }

        return $this->roles()->detach();
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !!$role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        if ($this->hasRole('ifsa')) {
            return true;
        }

        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
        }
        return $this->hasRole($permission->roles);
    }

    /**
     * Determine if the user is an IFS user. The user must have
     * an IFS group email address and at least one IFS role.
     *
     * @return boolean
     */
    public function hasIfsRole()
    {
        // Rudimentary check for IFS Group email address
        if (!stristr($this->email, 'ifsgroup.com')) {
            return false;
        }

        foreach ($this->roles->pluck('name') as $role) {
            if (stristr($role, 'ifs')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the user has more than one shipping role available (courier, air etc.)
     *
     * @return boolean
     */
    public function hasMultipleModes()
    {
        if ($this->hasRole('ifsa')) {
            return true;
        }

        $modes = Mode::all();

        $count = 0;

        foreach ($this->roles->where('primary', 0) as $role) {
            if ($modes->contains('name', $role->name)) {
                $count++;
            }
        }

        if ($count > 1) {
            return true;
        }

        return false;
    }

    /**
     * Returns the number of reports that the user has acceess to.
     * 
     * @return type
     */
    public function hasReports()
    {
        if ($this->hasRole('ifsa')) {
            return true;
        }

        if (!$this->hasPermission('view_reports')) {
            return false;
        }

        // Get the user's report permissions
        $count = $this->roles->where('primary', 1)->first()->permissions()->where('name', 'like', '%_report')->pluck('name')->count();

        if ($count > 0) {

            // Count the number of viable reports for user's allowed modes and depots.
            return \App\Report::whereIn('mode_id', $this->getAllowedModeIds())->whereIn('depot_id', $this->getDepotIds())->count();
        }

        return false;
    }

    /*
     * If a user does not have multiple modes enabled, this method will return
     * the one mode permission that they do have.
     * 
     * @return string
     */

    public function getOnlyMode()
    {
        if (!$this->hasMultipleModes()) {

            $role = $this->roles->where('primary', 0)->first();

            if ($role) {
                return $role->name;
            }
        }
        return false;
    }

    /**
     * 
     * @return string
     */
    public function getOnlyShipRoute()
    {
        $mode = $this->getOnlyMode();
        switch ($mode) {
            case 'sea':
                return 'sea-freight/create';
            default:
                return "/shipments/create?mode=$mode";
        }
    }

    /**
     * 
     * @return string
     */
    public function getOnlyHistoryRoute()
    {
        $mode = $this->getOnlyMode();
        switch ($mode) {
            case 'sea':
                return 'sea-freight';
            default:
                return "/shipments/?mode=$mode";
        }
    }

    /**
     * 
     * @return string
     */
    public function getDefaultRoute()
    {
        if ($this->hasRole('cudv') || $this->hasRole('ifsc')) {
            return '/customs-entries';
        }

        switch ($this->getOnlyMode()) {
            case 'sea':
                return 'sea-freight/create';
            default:
                return "/";
        }
    }

    /*
     * Returns the modes avaliable to the user.
     * 
     * @return 
     */

    public function modes()
    {
        // IFS Admin - return all modes
        if ($this->hasRole('ifsa')) {
            return Mode::all();
        }

        // Get the user's transport "mode" roles
        $roles = $this->roles->where('primary', 0)->pluck('name');

        return Mode::whereIn('name', $roles);
    }

    /*
     * Get the IDs of the modes available to the user.
     * 
     * 
     * @return array
     */

    public function getAllowedModeIds()
    {
        return $this->modes()->pluck('id');
    }

    /**
     * Returns the companies that the user is permitted to ship against. If the
     * user has IFS admin role, all companies are returned. If the user has IFS
     * manager role, all companies for their associated depots will be returned.
     * For all customers, only companies that have been defined for them will
     * be returned.
     * 
     * @param mixed $enabled Enabled status of the company - default returns all enabled and disabled companies. Boolean accepted - 0 disabled / 1 enabled
     * 
     * @return 
     */
    public function getAllowedCompanies($enabled = 'all')
    {
        switch ($enabled) {

            case 'all':

                if ($this->hasRole('ifsa')) {
                    return Company::all();
                }

                if ($this->hasIfsRole()) {
                    return Company::whereIn('depot_id', $this->getDepotIds())->get();
                }

                return $this->companies;

                break;

            default:

                if ($this->hasRole('ifsa')) {
                    return Company::whereEnabled($enabled)->get();
                }

                if ($this->hasIfsRole()) {
                    return Company::whereIn('depot_id', $this->getDepotIds())->whereEnabled($enabled)->get();
                }

                return $this->companies->where('enabled', $enabled);

                break;
        }
    }

    /**
     * Get an array of company IDs enabled for the user.
     * 
     * @return array    company IDs
     */
    public function getAllowedCompanyIds($enabled = 'all')
    {
        return $this->getAllowedCompanies($enabled)->pluck('id');
    }

    /**
     * Get an array of depot IDs associated with the user.
     * 
     * @return 
     */
    public function getDepotIds()
    {
        return $this->companies->pluck('depot_id')->unique();
    }

    /*
     * Returns the user's associated depots
     * 
     * @return 
     */

    public function depots()
    {
        if ($this->hasRole('ifsa')) {
            return \App\Depot::orderBy('name')->get();
        }

        return \App\Depot::whereIn('id', $this->getDepotIds())->orderBy('name')->get();
    }

    /**
     * Determine if a user is associated with a given depot.
     * 
     * @param type $depotId
     * @return boolean
     */
    public function hasDepot($depotId)
    {
        if (in_array($depotId, $this->getDepotIds()->toArray())) {
            return true;
        }
    }

    /**
     * Determines if a user is associated with a given model by comparing 
     * the user's list of allowed company ids with the model's company id.
     * 
     * @return boolean
     */
    public function relatedTo($related)
    {
        if ($related instanceof User) {

            $allowedCompanyIds = $this->getAllowedCompanyIds()->toArray();

            foreach ($related->companies as $company) {
                if (in_array($company->id, $allowedCompanyIds)) {
                    return true;
                }
            }
            return false;
        }

        if ($related instanceof Company) {
            $companyId = $related->id;
        } else {
            $companyId = $related->company_id;
        }

        if ($this->getAllowedCompanyIds()->contains($companyId)) {
            return true;
        }
        return false;
    }

    /**
     * Check if a user has more than one company.
     * 
     * @return boolean
     */
    public function hasMultipleCompanies()
    {
        if ($this->getAllowedCompanies()->count() > 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if a user has more than one company.
     * 
     * @return boolean
     */
    public function hasMultipleImportConfigs()
    {
        if ($this->getImportConfigs()->count() > 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if a user has more than one depot
     * 
     * @return boolean
     */
    public function hasMultipleDepots()
    {
        if (count($this->getDepotIds()) > 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if a user has at least one shipping mode enabled.
     * 
     * @return boolean
     */
    public function hasAtLeastOneMode()
    {
        if (count($this->getAllowedModeIds()) > 0) {
            return true;
        }

        return false;
    }

    /*
     * Returns the user's associated company site names
     * 
     * @return array
     */

    public function sites($enabled = 'all')
    {
        return $this->getAllowedCompanies($enabled)->sortBy('site_name')->pluck('site_name', 'id');
    }

    /*
     * Checks if the user has more than one enabled company.
     * 
     * @return boolean
     */

    public function hasEnabledCompanies()
    {
        if ($this->companies->where('enabled', 1)->count() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Check if the user is able to upload shipments.
     * 
     * @return boolean
     */
    public function canUploadShipments()
    {
        if ($this->hasRole('ifsa')) {
            return true;
        }

        if ($this->hasIfsRole() || count($this->getImportConfigs()) <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the defined import configurations available to the user.
     * 
     * @return type
     */
    public function getImportConfigs()
    {
        return \App\ImportConfig::select('id', 'company_name')->whereIn('company_id', $this->getAllowedCompanyIds(1))->where('enabled', 1)->orderBy('company_name')->get();
    }

    /*
     * Check that the user account has been configured correctly. A user must
     * have at least one company association and a one primary role defined.
     * 
     * @return boolean
     */

    public function isConfigured()
    {
        if ($this->hasEnabledCompanies() && $this->primary_role) {

            if ($this->hasRole('cudv') || $this->hasRole('ifsc')) {
                return true;
            }

            if ($this->hasAtLeastOneMode()) {
                return true;
            }
        }
        return false;
    }

}
