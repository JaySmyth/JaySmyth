<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles,
        HasPreferences,
        Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'telephone', 'enabled', 'localisation_id', 'print_format_id', 'driver_label', 'customer_label', 'show_search_bar'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login', 'created_at', 'updated_at'];

    /**
     * Set the user's name.
     *
     * @param  string  $value
     * @return string
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    /**
     * Set the user's email.
     *
     * @param  string  $value
     * @return string
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * User belongs to many companies.
     *
     * @return type
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    /**
     * A user has one localisation.
     *
     * @return
     */
    public function localisation()
    {
        return $this->belongsTo(Localisation::class);
    }

    /**
     * A user has one default print format.
     *
     * @return
     */
    public function printFormat()
    {
        return $this->belongsTo(PrintFormat::class);
    }

    /**
     * Grant the given company to a user.
     *
     * @param  Company $company
     * @return mixed
     */
    public function addCompany($companyId)
    {
        return $this->companies()->syncWithoutDetaching([$companyId]);
    }

    /**
     * Scope.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('name', 'LIKE', '%'.$filter.'%')
                            ->orWhere('email', 'LIKE', '%'.$filter.'%');
        }
    }

    /*
     * Scope restrict results by company.
     *
     * @param   array   $allowedCompanyIds  An array of company IDs that the user is enabled for.
     *
     */

    public function scopeRestrictByCompany($query, $allowedCompanyIds)
    {
        return $query->join('company_user', 'users.id', '=', 'company_user.user_id')
                        ->whereIn('company_user.company_id', $allowedCompanyIds);
    }

    /**
     * Scope enabled.
     *
     * @return
     */
    public function scopeHasEnabled($query, $enabled)
    {
        if (is_numeric($enabled)) {
            return $query->where('enabled', $enabled);
        }
    }

    /**
     * Scope role.
     *
     * @return
     */
    public function scopeHasRole($query, $role)
    {
        if (is_numeric($role)) {
            return $query->join('role_user', 'users.id', '=', 'role_user.user_id')
                            ->where('role_user.role_id', '=', $role);
        }
    }

    /**
     * If the user only has one company association, returns the company id.
     *
     * @return int or null
     */
    public function getCompanyIdAttribute()
    {
        return $this->companies->first()->id;
    }

    /**
     * If a user has only 1 import config enabled, return it's id.
     *
     * @return int or null
     */
    public function getImportConfigIdAttribute()
    {
        $importConfigs = $this->getImportConfigs();

        if ($importConfigs->count() == 1) {
            return $importConfigs->first()->id;
        }
    }

    /**
     * Get the user's main role id.
     *
     * @return int or null
     */
    public function getPrimaryRoleAttribute()
    {
        $role = $this->roles->where('primary', 1)->first();

        if ($role) {
            return $role->name;
        }
    }

    /**
     * Get the user's main role id.
     *
     * @return int or null
     */
    public function getPrimaryRoleLabelAttribute()
    {
        $role = $this->roles->where('primary', 1)->first();

        if ($role) {
            return $role->label;
        }
    }

    /**
     * Get the user's time zone.
     *
     * @return string
     */
    public function getTimeZoneAttribute()
    {
        return $this->localisation->time_zone;
    }

    /**
     * Returns PHP date format depending on user's localisation setting.
     *
     * @return string
     */
    public function getDateFormatAttribute()
    {
        return $this->localisation->php_date_format;
    }

    /**
     * Returns PHP date format depending on user's localisation setting.
     *
     * @return string
     */
    public function getVerboseDateFormatAttribute()
    {
        return $this->localisation->verbose_php_date_format;
    }

    /**
     * Get's a user's last login or returns an "inactive" message.
     *
     * @param type $timeZone
     * @param type $format
     * @return string
     */
    public function getLastLogin($timeZone = 'Europe/London', $format = 'd-m-Y H:i')
    {
        if ($this->last_login) {
            return $this->last_login->timezone($timeZone)->format($format);
        }

        return 'Inactive';
    }

    /**
     * Get viable notification messages for the user.
     *
     * @return type
     */
    public function getMessages()
    {
        // Company exclusions (NEEDS ADJUSTMENT - CURRENTLY A MESSAGE IS EXCLUDED IF ** ANY ** OF THE USERS COMPANY IDS ARE PRESENT - IDEALLY SHOULD ONLY EXCLUDE IF ALL OF THE USERS COMPANY IDS ARE PRESENT)
        $messages = \App\Message::active()
                ->whereHas('depots', function ($query) {
                    $query->whereIn('depot_id', $this->getDepotIds());
                })
                ->whereDoesntHave('companies', function ($query) {
                    $query->whereIn('company_id', $this->getAllowedCompanyIds());
                });

        // Not an IFS user, dont return any IFS only messages
        if (! $this->hasIfsRole()) {
            $messages->whereIfsOnly(0);
        }

        return $messages->get();
    }
}
