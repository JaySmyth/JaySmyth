<?php

namespace App\Models;

use App\Traits\Logable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class TransportJob extends Model
{
    use Logable;

    protected $fillable = [
        'number',
        'reference',
        'pieces',
        'weight',
        'goods_description',
        'volumetric_weight',
        'dimensions',
        'instructions',
        'final_destination',
        'closing_time',
        'pod_signature',
        'scs_job_number',
        'scs_company_code',
        'cash_on_delivery',
        'type',
        'completed',
        'from_type',
        'from_name',
        'from_company_name',
        'from_address1',
        'from_address2',
        'from_address3',
        'from_city',
        'from_state',
        'from_postcode',
        'from_country_code',
        'from_telephone',
        'from_email',
        'to_type',
        'to_name',
        'to_company_name',
        'to_address1',
        'to_address2',
        'to_address3',
        'to_city',
        'to_state',
        'to_postcode',
        'to_country_code',
        'to_telephone',
        'to_email',
        'visible',
        'depot_id',
        'shipment_id',
        'driver_manifest_id',
        'status_id',
        'department_id',
        'date_sent',
        'date_requested',
        'date_manifested',
        'date_completed',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_sent', 'date_requested', 'date_manifested', 'date_completed', 'closed_at', 'resend_date', 'created_at', 'updated_at'];

    /**
     * A transport job is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * A transport job is owned by a driver's manifest.
     *
     * @return
     */
    public function driverManifest()
    {
        return $this->belongsTo(DriverManifest::class);
    }

    /**
     * A transport job has one shipment.
     *
     * @return
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * A transport job has one status.
     *
     * @return
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * A transport job belongs to a department.
     *
     * @return
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the sender's country.
     *
     * @return string
     */
    public function getFromCountryAttribute()
    {
        return getCountry($this->from_country_code);
    }

    /**
     * Get the recipient's country.
     *
     * @return string
     */
    public function getToCountryAttribute()
    {
        return getCountry($this->to_country_code);
    }

    /**
     * Get source timezone.
     *
     * @return string
     */
    public function getSourceTimezoneAttribute()
    {
        return getTimezone($this->from_country_code, $this->from_state, $this->from_city);
    }

    /**
     * Get destination timezone.
     *
     * @return string
     */
    public function getDestinationTimezoneAttribute()
    {
        return getTimezone($this->to_country_code, $this->to_state, $this->to_city);
    }

    /**
     * Set the from postcode .
     *
     * @param string $value
     * @return string
     */
    public function setFromPostcodeAttribute($value)
    {
        $this->attributes['from_postcode'] = strtoupper($value);
    }

    /**
     * Set the from postcode .
     *
     * @param string $value
     * @return string
     */
    public function setToPostcodeAttribute($value)
    {
        $this->attributes['to_postcode'] = strtoupper($value);
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            $filter = trim($filter);

            return $query->where('number', 'LIKE', '%'.$filter.'%')
                ->orWhere('reference', 'LIKE', '%'.$filter.'%')
                ->orWhere('scs_job_number', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope status.
     *
     * @return
     */
    public function scopeHasStatus($query, $status)
    {
        if (is_numeric($status)) {
            return $query->where('status_id', $status);
        }

        if ($status) {
            $query->select('transport_jobs.*')->join('statuses', 'transport_jobs.status_id', '=', 'statuses.id');

            if (is_array($status)) {
                return $query->whereIn('statuses.code', $status);
            }

            return $query->where('statuses.code', '=', $status);
        }
    }

    /**
     * Scope depot.
     *
     * @return
     */
    public function scopeHasDepot($query, $depotId)
    {
        if (is_numeric($depotId)) {
            return $query->where('transport_jobs.depot_id', $depotId);
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
            return $query->where('transport_jobs.department_id', $departmentId);
        }
    }

    /**
     * Scope type.
     *
     * @return
     */
    public function scopeHasType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
    }

    /**
     * A job is cancellable.
     *
     * @return bool
     */
    public function isCancellable()
    {
        if ($this->isActive() && ! $this->shipment) {
            return true;
        }

        return false;
    }

    /**
     * Job active - i.e. not cancelled or completed.
     *
     * @return bool
     */
    public function isActive()
    {
        if ($this->status->code == 'cancelled' || $this->completed) {
            return false;
        }

        return true;
    }

    /**
     * Cancel a transport job. Set the status, send delete request to transend
     * and send mail notification to transport.
     */
    public function setCancelled()
    {
        $this->sent = false;
        $this->completed = true;
        $this->status_id = 7;
        $this->save();

        Mail::to('transport@antrim.ifsgroup.com')->queue(new \App\Mail\TransportJobCancelled($this));
    }

    /**
     * Remove job from driver manifest.
     */
    public function unmanifest()
    {
        $this->driver_manifest_id = null;
        $this->date_manifested = null;
        $this->save();

        $this->setStatus('unmanifested');
    }

    /**
     * Update the job status.
     *
     * @param type $status
     */
    public function setStatus($status)
    {
        // Look up the status
        if (is_numeric($status)) {
            $status = \App\Models\Status::find($status);
        } else {
            $status = \App\Models\Status::whereCode($status)->first();
        }

        if (! $status) {
            $status = \App\Models\Status::whereCode('unknown')->first();
        }

        $this->status_id = $status->id;
        $this->save();
    }

    /**
     * Close the job (set it to completed).
     *
     * @param type $userId
     * @param type $podDate
     * @param type $podSignature
     */
    public function close($podDatetime = null, $podSignature = 'Unknown', $userId = 0, $podImage = null, $podShipment = true)
    {
        $podDatetime = toCarbon($podDatetime);

        // Set the status of this job to completed
        $this->setStatus('completed');

        if (! $this->completed) {
            $this->completed = true;
            $this->pod_signature = $podSignature;
            $this->pod_image = $podImage;
            $this->pod_user = $userId;
            $this->date_completed = $podDatetime;
            $this->save();
        }

        if ($podShipment) {
            // If job linked to a shipment, and this is a delivery request, set the shipment to delivered (only POD IFS shipments)
            if ($this->shipment && $this->type == 'd' && $this->shipment->carrier->code == 'ifs') {
                $this->shipment->setDelivered($podDatetime, $podSignature, $userId, true, $podImage);
            }
        }
    }

    /**
     * Reverse out job closure.
     */
    public function undoClose()
    {
        $this->completed = false;
        $this->pod_signature = null;
        $this->pod_user = null;
        $this->date_completed = null;
        $this->save();

        // Set the status of this job to manifested
        $this->setStatus('manifested');

        // If job linked to a shipment, and this is a delivery request, reverse out the actions of setDelivered
        if ($this->shipment && $this->type == 'd') {
            $this->shipment->undoSetDelivered();
        }
    }

    /**
     * Un-manifested jobs.
     *
     * @return
     */
    public function unmanifested()
    {
        return $this->whereNull('driver_manifest_id')
            ->hasStatus('unmanifested')
            ->where('date_requested', '<', Carbon::now()->endOfDay())
            ->where('visible', '1')
            ->orderBy('from_company_name')
            ->orderBy('from_name')
            ->orderBy('to_company_name')
            ->orderBy('to_name')
            ->orderBy('date_requested')
            ->get();
    }

    /**
     * Takes a collection of transport jobs and returns as a formatted array.
     *
     * @param collection $transportJobs
     * @return array
     */
    public function groupByLocation($transportJobs)
    {
        $jobsByLocation = [];

        foreach ($transportJobs as $job) {
            if ($job->type == 'c') {
                $key = $job->from_address1.$job->from_postcode;
                inc($jobsByLocation[$key]['collections'], 1);
            } else {
                $key = $job->to_address1.$job->to_postcode;
                inc($jobsByLocation[$key]['deliveries'], 1);
            }

            inc($jobsByLocation[$key]['pieces'], $job->pieces);
            inc($jobsByLocation[$key]['weight'], $job->weight);
            inc($jobsByLocation[$key]['cod'], $job->cod);

            $jobsByLocation[$key]['jobs'][] = $job;
        }

        return $jobsByLocation;
    }

    /**
     * Job has been sent to TranSend successfully.
     */
    public function setToSent()
    {
        $this->sent = 1;
        $this->save();
    }

    /**
     * Mark job as resend.
     */
    public function resend($today = false)
    {
        $this->sent = 0;
        $this->is_resend = 1;
        $this->resend_date = ($today) ? Carbon::now() : Carbon::tomorrow();
        $this->attempts++;
        $this->save();
    }

    /**
     * Get routing info (collection/delivery route and time).
     *
     * @return array
     */
    public function getRoutingAttribute()
    {
        if ($this->type == 'c') {
            if ($this->shipment) {
                return $this->shipment->company->getCollectionSettingsForDay(Carbon::now()->dayOfWeekIso, $this->from_postcode);
            }

            return getRouting($this->from_postcode, Carbon::now()->dayOfWeekIso);
        }

        return getRouting($this->to_postcode, Carbon::now()->dayOfWeekIso);
    }

    /**
     * Set the transend route.
     *
     * @param type $route
     */
    public function setTransendRoute($route = false)
    {
        $this->transend_route = ($route) ? $route : $this->getTransendRoute();
        $this->transend_account_code = $this->getTransendAccountCode();
        $this->save();
    }

    /**
     * Get the transend route.
     *
     * @return string
     */
    public function getTransendRoute()
    {
        // Deliveries
        if ($this->type == 'd') {

            // Air deliveries
            if ($this->department_id == 6) {
                return $this->routing['delivery_route'];
            }

            // Courier delivery routes to trunk
            if ($this->shipment) {
                // Local deliveries - get delivery route from company collection/delivery settings or postcode table fallback
                if (in_array(strtoupper($this->shipment->service->code), ['NI24', 'NI48'])) {
                    return $this->routing['delivery_route'];
                }

                // Override Fedex UK delivery route (bulk collection customers)
                if (in_array(strtoupper($this->shipment->service->code), ['UK48', 'UK48S']) && $this->shipment->company->bulk_collections) {
                    return 'UK2';
                }

                // Override Fedex International BFS
                if ($this->shipment->route->code == 'BFS') {
                    return 'FEDF';
                }

                // Default to definition in services table
                if ($this->shipment->service->transend_route) {
                    return $this->shipment->service->transend_route;
                }
            }

            // Default to the code defined in departments table
            return ($this->department) ? $this->department->transend_code : 'ADHOC';
        }

        return (! empty($this->routing['collection_route'])) ? $this->routing['collection_route'] : 'ADHOC';
    }

    /**
     * Set the transend route.
     *
     * @param type $route
     */
    public function getTransendAccountCode()
    {
        // Deliveries, use company name and postcode
        if ($this->type == 'd') {
            return strtoupper(substr(preg_replace('/[^a-zA-Z0-9]+/', '', $this->to_company_name), 0, 3).'00'.preg_replace('/\s+/', '', $this->to_postcode).'-001');
        }

        // Collections with known SCS code
        if ($this->scs_company_code) {
            return $this->scs_company_code.'-002';
        }

        // Collections where SCS code isn't known, use company name and postcode
        return strtoupper(substr(preg_replace('/[^a-zA-Z0-9]+/', '', $this->from_company_name), 0, 3).'00'.preg_replace('/\s+/', '', $this->from_postcode).'-001');
    }
}
