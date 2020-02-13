<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SeaFreightShipment extends Model
{
    /*
     * Mass assignable.
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['required_on_dock_date', 'estimated_departure_date', 'departure_date', 'estimated_arrival_date', 'arrival_date', 'delivery_date', 'created_at', 'updated_at'];

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
    public function shippingLine()
    {
        return $this->belongsTo(ShippingLine::class);
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
    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    /**
     * A shipment has many tracking events.
     *
     * @return
     */
    public function tracking()
    {
        return $this->hasMany(SeaFreightTracking::class)->orderBy('datetime', 'DESC');
    }

    /**
     * A customs entry has many documents.
     *
     * @return
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_sea_freight_shipment', 'shipment_id')->orderBy('id', 'DESC');
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
     * A shipment has one status.
     *
     * @return
     */
    public function seaFreightStatus()
    {
        return $this->belongsTo(SeaFreightStatus::class);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setRequiredOnDockDateAttribute($value)
    {
        $this->attributes['required_on_dock_date'] = Carbon::parse($value);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setEstimatedDepartureDateAttribute($value)
    {
        $this->attributes['estimated_departure_date'] = Carbon::parse($value);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setDepartureDateAttribute($value)
    {
        $this->attributes['departure_date'] = Carbon::parse($value);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setEstimatedArrivalDateAttribute($value)
    {
        $this->attributes['estimated_arrival_date'] = Carbon::parse($value);
    }

    /**
     * Set the date.
     *
     * @param  string  $value
     * @return string
     */
    public function setArrivalDateAttribute($value)
    {
        $this->attributes['arrival_date'] = Carbon::parse($value);
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
     * Set the shipment reference.
     *
     * @param  string  $value
     * @return string
     */
    public function setFinalDestinationAttribute($value)
    {
        $this->attributes['final_destination'] = strtoupper($value);
    }

    /**
     * Set the shipment reference.
     *
     * @param  string  $value
     * @return string
     */
    public function setPortOfLoadingAttribute($value)
    {
        $this->attributes['port_of_loading'] = strtoupper($value);
    }

    /**
     * Set the shipment reference.
     *
     * @param  string  $value
     * @return string
     */
    public function setPortOfDischargeAttribute($value)
    {
        $this->attributes['port_of_discharge'] = strtoupper($value);
    }

    /**
     * Set the shipment reference.
     *
     * @param  string  $value
     * @return string
     */
    public function setSeaWayBillAttribute($value)
    {
        $this->attributes['bill_of_lading'] = strtoupper($value);
    }

    /**
     * Get the number of days that the shipment has been in transit.
     *
     * @return  int (days in transit)
     */
    public function getTimeInTransitAttribute()
    {
        if ($this->departure_date && $this->arrival_date) {
            return $this->departure_date->diffInDays($this->arrival_date);
        }

        if ($this->departure_date) {
            return $this->departure_date->diffInDays(Carbon::now());
        }

        return 0;
    }

    /**
     * Get the time remaining until on dock date.
     *
     * @return  int (hours in transit)
     */
    public function getTimeRemainingAttribute()
    {
        if (in_array($this->sea_freight_status_id, [12])) {
            return 0;
        }

        if ($this->arrival_date) {
            return $this->arrival_date->diffInDays($this->required_on_dock_date, false);
        } else {
            return Carbon::now()->diffInDays($this->required_on_dock_date, false);
        }
    }

    /**
     * Get the shipper.
     *
     * @return string
     */
    public function getShipperAttribute()
    {
        if ($this->company) {
            return $this->company->company_name;
        }
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('number', 'LIKE', '%'.$filter.'%')
                            ->orWhere('final_destination', 'LIKE', '%'.$filter.'%')
                            ->orWhere('reference', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (! $dateFrom && $dateTo) {
            return $query->where('required_on_dock_date', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && ! $dateTo) {
            return $query->where('created_at', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->where('created_at', '>', Carbon::parse($dateFrom)->startOfDay())
                            ->where('required_on_dock_date', '<', Carbon::parse($dateTo)->endOfDay());
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
     * Scope status.
     *
     * @return
     */
    public function scopeHasStatus($query, $status)
    {
        // Shipped (all received shipments - not cancelled)
        if ($status == 'active') {
            return $query->whereNotIn('sea_freight_status_id', [11, 12]);
        }

        if (is_numeric($status)) {
            return $query->where('sea_freight_status_id', $status);
        }

        if ($status) {
            $query->select('sea_freight_shipments.*')->join('sea_freight_statuses', 'sea_freight_shipments.sea_freight_status_id', '=', 'sea_freight_statuses.id');

            if (is_array($status)) {
                return $query->whereIn('sea_freight_statuses.code', $status);
            }

            return $query->where('sea_freight_statuses.code', '=', $status);
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
     * Sets the shipment to delivered. Updates delivered flag, delivery date and signature.
     *
     * @param   string  $podSignature
     * @param   carbon  $deliveryDate
     *
     * @return  void
     */
    public function setDelivered($podSignature = 'Unknown', $deliveryDate = null, $userId = 0, $timeZone = false)
    {
        if (! $this->delivered) {
            if (! $deliveryDate instanceof Carbon) {
                $deliveryDate = Carbon::now();
            }

            if ($timeZone) {
                $deliveryDate->timezone($timeZone);
            }

            $this->delivered = true;
            $this->pod_signature = $podSignature;
            $this->delivery_date = $deliveryDate;
            $this->save();

            // Set the shipment status to "delivered"
            $this->setStatus('delivered', $userId, $deliveryDate);
        }
    }

    /**
     * Set the status of the shipment and adds a tracking event to show that
     * the status has changed.
     *
     * @param string  $statusCode Status that the shipment will be changed to
     * @param int $userId User changing the shipment status
     * @param bool $withTrackingEvent
     *
     * @return null
     */
    public function setStatus($status, $userId = 0, $datetime = false, $message = false, $withTrackingEvent = true)
    {
        // Look up the status
        if (is_numeric($status)) {
            $status = SeaFreightStatus::find($status);
        } else {
            $status = SeaFreightStatus::whereCode($status)->first();
        }

        // Only update if we have been given a valid status and the shipment is not currently set to this status
        if ($status && $this->sea_freight_status_id != $status->id) {
            if ($status->id > 4 && $this->isActive() && ! $this->departure_date) {
                $this->departure_date = Carbon::parse($datetime);
            }

            if ($status->id > 6 && $this->isActive() && ! $this->arrival_date) {
                $this->arrival_date = Carbon::parse($datetime);
            }

            // Change the status on the shipment record
            $this->sea_freight_status_id = $status->id;
            $this->save();

            // Add a tracking event for this status change
            if ($withTrackingEvent) {
                $this->addTracking($status->code, $userId, Carbon::parse($datetime), $message);
            }
        }
    }

    /**
     * Adds a tracking event to the shipment. Defaults the tracking location
     * to that of the shipment's associated depot. Requires a valid status as
     * a minimum requirement. If no message has been provided, a standard
     * message is retreived from the statuses table.
     *
     * @param string $status Status that the shipment will be changed to
     * @param int $userId User adding the tracking event
     * @param string $message Tracking message
     * @param string $datetime Date/time of the event
     *
     * @return mixed
     */
    public function addTracking($status, $userId = 0, $datetime = false, $message = false)
    {
        // check the status if valid
        $status = SeaFreightStatus::whereCode($status)->first();

        if ($status) {
            if (! $message) {
                $message = $status->description;
            }

            if (! $datetime) {
                $datetime = Carbon::now();
            }

            return SeaFreightTracking::create([
                        'status' => $status->code,
                        'status_name' => $status->name,
                        'message' => $message,
                        'datetime' => $datetime,
                        'user_id' => $userId,
                        'sea_freight_shipment_id' => $this->id,
            ]);
        }

        return false;
    }

    /**
     * Shipment active - i.e. not cancelled or delivered.
     *
     * @return bool
     */
    public function isActive()
    {
        if ($this->seaFreightStatus->code == 'cancelled' || $this->seaFreightStatus->code == 'delivered') {
            return false;
        }

        return true;
    }

    /**
     * A shipment is cancellable.
     *
     * @return bool
     */
    public function isCancellable()
    {
        $cancellableStatuses = ['new', 'booking'];

        if (in_array($this->seaFreightStatus->code, $cancellableStatuses)) {
            return true;
        }

        return false;
    }
}
