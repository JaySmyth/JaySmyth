<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Quotation extends Model
{

    use Logable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['valid_to', 'created_at', 'updated_at'];

    /**
     * A quote is owned by a depot.
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A quote is owned by a department.
     *
     * @return
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Set valid to.
     * 
     * @param type $value
     */
    public function setValidToAttribute($value)
    {
        $this->attributes['valid_to'] = Carbon::createFromformat('d-m-Y', $value);
    }

    /**
     * Reference number.
     * 
     * @return string
     */
    public function getReferenceAttribute()
    {
        return $this->department->code . str_pad($this->id, 8, 0, STR_PAD_LEFT);
    }

    /**
     * Toggles the success flag.
     *
     * @return null
     */
    public function toggleSuccessful()
    {
        $this->successful = ($this->successful) ? false : true;
        $this->save();

        return $this->successful;
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('id', preg_replace('/[^0-9]/', '', $filter))
                            ->orWhere('company_name', 'LIKE', '%' . $filter . '%')
                            ->orWhere('contact', 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * Scope department.
     *
     * @return
     */
    public function scopeHasDepartment($query, $departmentId)
    {
        if (is_numeric($departmentId)) {
            return $query->where('department_id', $departmentId);
        }
    }

    /**
     * Scope successful.
     *
     * @return
     */
    public function scopeHasSuccessful($query, $successful)
    {
        if (strlen($successful) > 0) {
            return $query->whereSuccessful($successful);
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (!$dateFrom && $dateTo) {
            return $query->where('created_at', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && !$dateTo) {
            return $query->where('created_at', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('created_at', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

}
