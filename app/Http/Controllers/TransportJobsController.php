<?php

namespace App\Http\Controllers;

use App\CarrierAPI\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransportJobRequest;
use App\TransportJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TransportJobsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List transport jobs.
     *
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $this->authorize(new TransportJob);

        $transportJobs = $this->search($request);

        return view('transport_jobs.index', compact('transportJobs'));
    }

    /**
     * Show a job.
     *
     * @param type $id
     * @return type
     */
    public function show($id)
    {
        $transportJob = TransportJob::findOrFail($id);

        $this->authorize($transportJob);

        return view('transport_jobs.show', compact('transportJob'));
    }

    /**
     * Display transport job form.
     *
     * @param Request $request
     * @return type
     */
    public function create(Request $request)
    {
        $this->authorize(new TransportJob);

        $type = ($request->type) ? $request->type : 'collection';

        $submitButtonText = ($type == 'delivery') ? 'Create Delivery Request' : 'Create Collection Request';

        $address = ($type == 'delivery') ? 'to' : 'from';

        return view('transport_jobs.create', ['type' => $type, 'submitButtonText' => $submitButtonText, 'address' => $address]);
    }

    /**
     * Store transport job.
     *
     * @param Request $request
     * @return type
     */
    public function store(TransportJobRequest $request)
    {
        $this->authorize(new TransportJob);

        $address = ($request->type == 'delivery') ? 'to' : 'from';

        $depot = \App\Depot::find($request->depot_id);

        $values = [
            'number' => \App\Sequence::whereCode('JOB')->lockForUpdate()->first()->getNextAvailable(),
            'date_requested' => gmtToCarbonUtc($request->date.' '.$request->time),
            $address.'_type' => 'c',
            $address.'_name' => 'Transport Department',
            $address.'_company_name' => $depot->address1,
            $address.'_address1' => $depot->address2,
            $address.'_city' => $depot->city,
            $address.'_state' => $depot->state,
            $address.'_postcode' => $depot->postcode,
            $address.'_country_code' => $depot->country_code,
            $address.'_telephone' => $depot->telephone,
            $address.'_email' => $depot->email,
            'instructions' => $request->instructions,
        ];

        $array = array_merge($request->all(), $values);

        $transportJob = TransportJob::create($array);

        $transportJob->setTransendRoute();

        $transportJob->setStatus('unmanifested');

        $transportJob->log();

        Mail::to('transport@antrim.ifsgroup.com')->cc($request->user()->email)->queue(new \App\Mail\TransportJobCreated($transportJob));

        flash()->success('Created!', 'Job created successfully.');

        return redirect('transport-jobs');
    }

    /**
     * Display edit job form.
     *
     * @param type $id
     * @return type
     */
    public function edit($id)
    {
        $transportJob = TransportJob::findOrFail($id);

        $this->authorize($transportJob);

        $type = 'Collection';
        $address = 'from';

        if ($transportJob->type == 'd') {
            $type = 'Delivery';
            $address = 'to';
        }

        return view('transport_jobs.edit', compact('transportJob'))->with('type', $type)->with('address', $address);
    }

    /**
     * Update the job.
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function update($id, TransportJobRequest $request)
    {
        $transportJob = TransportJob::findOrFail($id);

        $this->authorize($transportJob);

        $array = array_merge($request->all(), ['date_requested' => gmtToCarbonUtc($request->date.' '.$request->time)]);

        $transportJob->updateWithLog($array);

        flash()->success('Updated!', 'Job updated successfully.');

        return redirect('transport-jobs');
    }

    /**
     * Display close job form.
     *
     * @return type
     */
    public function close()
    {
        $this->authorize(new TransportJob);

        $cutOff = new Carbon('yesterday');

        $transportJobs = TransportJob::whereCompleted(0)
                ->where('status_id', '!=', 7)
                ->where('date_requested', '<', $cutOff->endOfDay())
                ->orderBy('date_requested', 'ASC')
                ->get();

        $deliveries = $transportJobs->where('type', 'd');
        $collections = $transportJobs->where('type', 'c')->where('shipment_id', '<=', 0);

        return view('transport_jobs.close', compact('deliveries', 'collections'));
    }

    /**
     * Set job to closed.
     *
     * @param Request $request
     * @return type
     */
    public function setClosed(Request $request)
    {
        $this->authorize('close', new TransportJob);

        $this->validate($request, ['number' => 'required', 'signature' => 'required|regex:/^[a-zA-Z ]+$/']);

        $transportJob = TransportJob::where(function ($query) use ($request) {
            $query->where('number', $request->number)->orWhere('reference', $request->number);
        })
                ->orderBy('id', 'DESC')
                ->first();

        if ($transportJob) {
            $datetime = gmtToCarbonUtc($request->date.' '.$request->time);

            // Completed collection, try find ROI shipment that requires POD
            if ($transportJob->shipment && $transportJob->completed && $transportJob->type == 'c' && in_array(strtoupper($transportJob->shipment->service->carrier_code), ['IE24', 'IE48'])) {
                $transportJob->shipment->setDelivered($datetime, $request->signature, $request->user()->id, true);
            } else {
                $transportJob->close($datetime, $request->signature, $request->user()->id);
                $transportJob->log('Job closed');
            }

            flash()->success('Job Closed');

            return back();
        }

        flash()->error('Error!', 'Job Number not recognised.', true);

        return back();
    }

    /**
     * Set a job to cancelled.
     *
     * @param
     * @return
     */
    public function cancel(Request $request, $id)
    {
        if ($request->ajax()) {
            $transportJob = TransportJob::findOrFail($id);

            $this->authorize($transportJob);

            $transportJob->setCancelled();

            $transportJob->log('Job cancelled');
        }
    }

    /**
     * Set a job to collected.
     *
     * @param
     * @return
     */
    public function collect(Request $request, $id)
    {
        if ($request->ajax()) {
            $transportJob = TransportJob::findOrFail($id);

            $this->authorize('unmanifest', $transportJob);

            if ($transportJob->type == 'c' && $transportJob->shipment_id <= 0) {
                $transportJob->close(null, $transportJob->driverManifest->driver->name, $request->user()->id);
            } else {
                $transportJob->setStatus('collected');
            }
        }
    }

    /**
     * Remove a transport job from a driver manifest.
     *
     * @param Request $request
     * @return type
     */
    public function unmanifest(Request $request, $id)
    {
        $transportJob = TransportJob::findOrFail($id);

        $this->authorize($transportJob);

        $transportJob->unmanifest();

        flash()->success('Job removed!');

        return back();
    }

    /**
     * List unmanifested jobs.
     *
     * @param Request $request
     * @return type
     */
    public function unmanifested(Request $request)
    {
        $this->authorize('manifestJobs', new TransportJob);

        $transportJob = new TransportJob();

        $unmanifestedJobs = $transportJob->unmanifested();
        $totalJobs = $unmanifestedJobs->count();

        $transportJobs = [];

        // Collections - sorted by postcode, then grouped by route
        foreach ($unmanifestedJobs->where('type', 'c')->sortBy('from_postcode', SORT_NATURAL) as $job) {
            $transportJobs['collections'][$job->transend_route][] = $job;
        }

        // Deliveries - sorted by postcode, then grouped by route
        foreach ($unmanifestedJobs->where('type', 'd')->sortBy('to_postcode', SORT_NATURAL) as $job) {
            $transportJobs['deliveries'][$job->transend_route][] = $job;
        }

        $driverManifest = new \App\DriverManifest();
        $driverManifests = $driverManifest->getOpenManifests();

        return view('transport_jobs.unmanifested', compact('transportJobs', 'driverManifests', 'totalJobs'));
    }

    /**
     * Allocate jobs to driver.
     *
     * @param Request $request
     * @return type
     */
    public function manifestJobs(Request $request)
    {
        $this->authorize(new TransportJob);

        $this->validate($request, ['jobs' => 'required', 'driver_manifest_id' => 'required'], ['jobs.required' => 'You must check at least one job to be manifested!', 'driver_manifest_id.required' => 'You must select a driver!']);

        $jobIds = array_keys($request->jobs);

        // update all the selected jobs with the driver manifest id
        TransportJob::whereIn('id', $jobIds)->update([
            'driver_manifest_id' => $request->driver_manifest_id,
            'date_manifested' => Carbon::now(),
            'status_id' => 14,
        ]);

        $count = '1 job';

        if (count($jobIds) > 1) {
            $count = count($jobIds).' jobs';
        }

        $driverManifest = \App\DriverManifest::find($request->driver_manifest_id);

        flash()->success('Jobs Manifested!', $count.' manifested to '.$driverManifest->driver->name.' successfully.', true);

        return redirect('transport-jobs/unmanifested');
    }

    /**
     * Download POD docket.
     *
     * @param Request $request
     * @return type
     */
    public function docket(Request $request, $id)
    {
        $transportJob = TransportJob::findOrFail($id);

        $this->authorize('view', $transportJob);

        $pdf = new Pdf($request->user()->printFormat->code, 'D');

        return $pdf->createPodDocket($transportJob);
    }

    /**
     * Email POD dockets to transport dept.
     * @return type
     */
    public function emailDockets(Request $request)
    {
        $this->authorize('manifestJobs', new TransportJob);

        dispatch(new \App\Jobs\GeneratePodDockets($request->user()));

        // Notify user and redirect
        flash()->success('Email Sent', 'Dockets for tomorrow emailed to transport', true);

        return back();
    }

    /**
     * TransportJob search.
     *
     * @param type $request
     * @param type $paginate
     * @param type $limit
     * @return type
     */
    private function search($request, $paginate = true, $limit = false)
    {
        $query = TransportJob::orderBy('id', 'DESC')
                ->filter($request->filter)
                ->hasType($request->type)
                ->hasStatus($request->status)
                ->hasDepartment($request->department)
                ->where('visible', '1');

        if ($limit) {
            $query->limit($limit);
        }

        if (! $paginate) {
            return $query->get();
        }

        return $query->paginate(50);
    }
}
