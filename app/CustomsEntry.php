<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomsEntry extends Model
{
    /*
     * Mass assignable.
     */

    protected $fillable = ['company_id', 'reference', 'number', 'consignment_number', 'additional_reference', 'date', 'scs_job_number', 'commercial_invoice_value', 'commercial_invoice_value_currency_code', 'customs_value', 'pieces', 'weight', 'commodity_count', 'country_of_origin', 'user_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * A customs entry is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A customs entry is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * A customs entry has many commodity lines.
     *
     * @return
     */
    public function customsEntryCommodity()
    {
        return $this->hasMany(CustomsEntryCommodity::class);
    }

    /**
     * A customs entry has many documents.
     *
     * @return
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class)->orderBy('id', 'DESC');
    }

    /**
     * A customs entry is owned by a user.
     *
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromformat('d-m-Y', $value);
    }

    /**
     * Set the reference.
     *
     * @param  string  $value
     * @return string
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = strtoupper($value);
    }

    /**
     * Set the reference.
     *
     * @param  string  $value
     * @return string
     */
    public function setReferenceAttribute($value)
    {
        $this->attributes['reference'] = strtoupper($value);
    }

    /**
     *
     * @return int
     */
    public function getDutyAttribute()
    {
        return $this->customsEntryCommodity()->sum('duty');
    }

    /**
     *
     * @return int
     */
    public function getVatAttribute()
    {
        return $this->customsEntryCommodity()->sum('vat');
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('number', 'LIKE', '%' . $filter . '%')
                            ->orWhere('consignment_number', 'LIKE', '%' . $filter . '%')
                            ->orWhere('reference', 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeAdditionalRef($query, $filter)
    {
        if ($filter) {
            return $query->where('additional_reference', 'LIKE', '%' . $filter . '%');
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
            return $query->where('date', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && !$dateTo) {
            return $query->where('date', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('date', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

    /**
     * Scope company.
     *
     * @return
     */
    public function scopeHasCompany($query, $companyId)
    {
        if (is_numeric($companyId)) {
            return $query->where('company_id', $companyId);
        }
    }

    /**
     * Scope restrict results by company.
     *
     * @param type $query
     * @param type $companyIds
     * @return type
     */
    public function scopeRestrictCompany($query, $companyIds)
    {
        return $query->whereIn('company_id', $companyIds);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeHasCpc($query, $cpcId)
    {
        if (is_numeric($cpcId)) {
            return $query->select('customs_entries.*')
                            ->join('customs_entry_commodities', 'customs_entries.id', '=', 'customs_entry_commodities.customs_entry_id')
                            ->where('customs_entry_commodities.customs_procedure_code_id', '=', $cpcId)
                            ->groupBy('customs_entries.id');
        }
    }

    /**
     * Check that the entry has been completed.
     *
     * @return boolean
     */
    public function isComplete()
    {

        // Fields to check values of
        if (!empty($this->company_id)) {
            $fullDutyAndVat = Company::find($this->company_id)->full_dutyandvat;
            if ($fullDutyAndVat) {
                $fields = ['company_id', 'number', 'reference', 'scs_job_number', 'commercial_invoice_value', 'commercial_invoice_value_currency_code', 'customs_value', 'pieces', 'commodity_count', 'weight'];
            } else {
                $fields = ['company_id', 'number', 'reference'];
            }
        }

        foreach ($fields as $field) {
            if (empty($this->$field) || $this->$field == '0.00') {
                return false;
            }
        }

        if ($this->customsEntryCommodity->count() != $this->commodity_count) {
            return false;
        }

        return true;
    }
}
