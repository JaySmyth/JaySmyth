<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DriverManifest extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * A transport manifest is owned by a depot.
     *
     * @return
     */
    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * A transport manifest is owned by a depot.
     *
     * @return
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * A transport manifest is owned by a depot.
     *
     * @return
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
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

            return $query->where('number', 'LIKE', '%' . $filter . '%');
        }
    }

    /**
     * Scope driver.
     *
     * @return
     */
    public function scopeHasDriver($query, $driverId)
    {
        if (is_numeric($driverId)) {
            return $query->where('driver_id', $driverId);
        }
    }

    /**
     * Scope vehicle.
     *
     * @return
     */
    public function scopeHasVehicle($query, $vehicleId)
    {
        if (is_numeric($vehicleId)) {
            return $query->where('vehicle_id', $vehicleId);
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
            return $query->where('driver_manifests.depot_id', $depotId);
        }
    }

    /**
     * Scope date.
     *
     * @return
     */
    public function scopeHasDate($query, $date)
    {
        if ($date) {
            return $query->whereBetween('date', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()]);
        }
    }

    /**
     * Scope jobs.
     *
     * @return
     */
    public function scopeHasJobs($query)
    {
        $query->select('driver_manifests.*')
                ->join('transport_jobs', 'driver_manifests.id', '=', 'transport_jobs.driver_manifest_id')
                ->groupBy('driver_manifests.id');
    }

    /**
     * A manifest has many jobs
     *
     * @return
     */
    public function transportJobs()
    {
        return $this->hasMany(TransportJob::class)
                        ->orderBy('from_state')
                        ->orderBy('from_city')
                        ->orderBy('from_postcode')
                        ->orderBy('from_company_name')
                        ->orderBy('from_name')
                        ->orderBy('to_state')
                        ->orderBy('to_city')
                        ->orderBy('to_postcode')
                        ->orderBy('to_company_name')
                        ->orderBy('to_name');
    }

    /**
     *
     * @return type
     */
    public function collections()
    {
        return $this->transportJobs->where('type', 'c');
    }

    /**
     *
     * @return type
     */
    public function deliveries()
    {
        return $this->transportJobs->where('type', 'd');
    }

    /**
     *
     *
     * @return int
     */
    public function getCollectionCountAttribute()
    {
        return $this->transportJobs->where('type', 'c')->count();
    }

    /**
     *
     *
     * @return int
     */
    public function getDeliveryCountAttribute()
    {
        return $this->transportJobs->where('type', 'd')->count();
    }

    /**
     *
     *
     * @return int
     */
    public function getTotalCountAttribute()
    {
        return $this->transportJobs->count();
    }

    /**
     * Total pieces to collect.
     *
     * @return int
     */
    public function getPiecesToCollectAttribute()
    {
        return $this->collections()->sum('pieces');
    }

    /**
     * Total pieces to collect.
     *
     * @return int
     */
    public function getPiecesToDeliverAttribute()
    {

        return $this->deliveries()->sum('pieces');
    }

    /**
     * Count locations.
     * 
     * @return integer
     */
    public function getLocationCountAttribute()
    {
        return count($this->getJobsByLocation());
    }

    /**
     * Total weight for the manifest.
     *
     * @return int
     */
    public function getWeightAttribute()
    {
        return number_format($this->transportJobs->sum('weight'), 2);
    }

    /**
     * Total volume for the manifest.
     *
     * @return int
     */
    public function getVolumetricWeightAttribute()
    {
        return number_format($this->transportJobs->sum('volumetric_weight'), 2);
    }

    /**
     * Takes a collection of transport jobs and returns as a formatted array.
     * 
     * @return array
     */
    public function getJobsByLocation()
    {
        $jobsByLocation = array();

        foreach ($this->transportJobs as $job) {

            if ($job->type == 'c') {
                $key = $job->from_address1 . $job->from_postcode;
                inc($jobsByLocation[$key]['collections'], 1);
            } else {
                $key = $job->to_address1 . $job->to_postcode;
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
     *
     * @return type
     */
    public function getOpenManifests()
    {
        return $this->where('driver_manifests.closed', 0)
                        ->select('driver_manifests.id', DB::raw('CONCAT(drivers.name, " - ", DATE_FORMAT(driver_manifests.date,\'%W %D %M\'), " - ", vehicles.registration, " - ", vehicles.type, " - ", count(transport_jobs.driver_manifest_id), " jobs") AS manifest'))
                        ->join('drivers', 'driver_manifests.driver_id', '=', 'drivers.id')
                        ->join('vehicles', 'driver_manifests.vehicle_id', '=', 'vehicles.id')
                        ->leftJoin('transport_jobs', 'driver_manifests.id', '=', 'transport_jobs.driver_manifest_id')
                        ->orderBy('manifest')
                        ->groupBy('driver_manifests.id')
                        ->get();
    }

    /**
     * Close a manifest - if no jobs on manifest, delete it.
     */
    public function close()
    {
        if ($this->total_count == 0) {
            $this->destroy($this->id);
        } else {
            $this->closed = true;
            $this->save();
        }
    }

    /**
     * Determine if it is possible to reopen a manifest.
     * 
     * @return boolean
     */
    public function isOpenable()
    {
        if ($this->closed && \Carbon\Carbon::today() <= $this->date) {
            return true;
        }
        return false;
    }

}
