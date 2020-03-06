<?php

namespace App\Http\Controllers;

use App\Models\Models\Carrier;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrackingRequest;
use App\Models\Shipment;
use App\Models\Tracking;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'track',
                'trackShipment',
                'show',
                'easypostWebhook',
                'tracker',
            ],
        ]);
    }

    /**
     * Display track shipment form.
     *
     * @param
     * @return
     */
    public function track()
    {
        if (Auth::Check()) {
            return view('tracking.track');
        }

        return view('tracking.public_track');
    }

    /**
     * Show tracking events for a shipment.
     *
     * @param
     * @return
     */
    public function trackShipment(Request $request)
    {
        $shipment = Shipment::whereConsignmentNumber($request->tracking_number)->orWhere('carrier_tracking_number', $request->tracking_number)->first();

        if (! $shipment) {
            return redirect('track')->withInput()->withErrors(['tracking_number' => 'No tracking information found!']);
        }

        if (Auth::Check() && $request->user()->relatedTo($shipment)) {
            return redirect('shipments/'.$shipment->id);
        }

        return view('tracking.show', compact('shipment'));
    }

    /**
     * Show tracking events for a shipment (GET).
     *
     * @param
     * @return
     */
    public function tracker($consignmentNumber)
    {
        $shipment = Shipment::whereConsignmentNumber($consignmentNumber)->orWhere('carrier_tracking_number', $consignmentNumber)->first();

        if (! $shipment) {
            return redirect('track')->withInput()->withErrors(['tracking_number' => 'No tracking information found!']);
        }

        return view('tracking.show', compact('shipment'));
    }

    /**
     * Show tracking events for a shipment.
     *
     * @param
     * @return
     */
    public function show($token, $type = null)
    {
        $shipment = Shipment::where('token', $token)->firstOrFail();

        return view('tracking.show', compact('shipment', 'type'));
    }

    /**
     * Create a tracking event.
     *
     * @param
     * @return
     */
    public function create($shipmentId)
    {
        $shipment = Shipment::findOrFail($shipmentId);

        return view('tracking.create', compact('shipment'));
    }

    /**
     * Store tracking record.
     *
     * @param TrackingRequest $request
     * @return type
     */
    public function store(TrackingRequest $request)
    {
        $shipment = Shipment::findOrFail($request->shipment_id);

        $datetimeString = $request->date.' '.$request->time;
        $datetime = gmtToCarbonUtc($datetimeString);

        $estimatedDeliveryDate = null;
        $localEstimatedDeliveryDate = null;

        if ($request->estimated_delivery_date && $request->estimated_delivery_time) {
            $estimatedDeliveryDate = gmtToCarbonUtc($request->estimated_delivery_date.' '.$request->estimated_delivery_time);
            $localEstimatedDeliveryDate = strtotime($request->estimated_delivery_date.' '.$request->estimated_delivery_time);
        }

        $tracking = Tracking::firstOrCreate([
            'message' => $request->message,
            'status' => $request->status_code,
            'datetime' => $datetime,
            'local_datetime' => strtotime($request->date.' '.$request->time),
            'carrier' => 'ifs',
            'city' => $request->city,
            'state' => $request->state,
            'country_code' => $request->country_code,
            'postcode' => $request->postcode,
            'tracker_id' => Str::random(12),
            'source' => 'ifs',
            'estimated_delivery_date' => $estimatedDeliveryDate,
            'local_estimated_delivery_date' => $localEstimatedDeliveryDate,
            'user_id' => $request->user()->id,
            'shipment_id' => $shipment->id,
        ]);

        if ($tracking) {
            switch ($request->status_code) {
                case 'pre_transit':
                    $shipment->received = false;
                    $shipment->delivered = false;
                    $shipment->save();
                    $shipment->setStatus($request->status_code, $request->user()->id, false, false);
                    break;
                case 'received':
                    $shipment->received = true;
                    $shipment->delivered = false;
                    $shipment->save();
                    $shipment->setStatus($request->status_code, $request->user()->id, false, false);
                    break;
                case 'delivered':
                    $shipment->setDelivered($datetimeString, $request->message, $request->user()->id);
                    break;
                default:
                    $shipment->setStatus($request->status_code, $request->user()->id, false, false);
                    break;
            }
        }

        flash()->success('Tracking Added!', 'Tracking event has been added successfully.');

        return redirect('shipments/'.$shipment->id);
    }

    /**
     * Delete a tracking event.
     *
     * @param type $id
     * @return type
     */
    public function destroy($id)
    {
        return Tracking::where('id', $id)
            ->where('user_id', '>', 0)
            ->where('status', '!=', 'received')
            ->delete();
    }

    /**
     * Called by easypost via json post. Inserts a tracking event to the database.
     *
     * @param  $request     json
     *
     * @return http response
     */
    public function easypostWebhook(Request $request)
    {
        //\Debugbar::disable();

        if ($request->isJson()) {
            $result = $request->json('result');

            if (isset($result['carrier'])) {
                if ($result['carrier'] == 'UPS') {
                    $carrier = Carrier::find(3);
                } else {
                    // Load the carrier
                    $carrier = Carrier::whereEasypost($result['carrier'])->firstOrFail();
                }

                // Load the shipment from the tracking code and carrier
                $shipment = Shipment::whereCarrierTrackingNumber($result['tracking_code'])->firstOrFail();

                dispatch(new \App\Jobs\HandleEasypostWebhook($result, $shipment));
            }
        }
    }

    /**
     * Display track shipment form.
     *
     * @param
     * @return
     */
    public function createEasypostTracker()
    {
        $carriers = \App\Models\Models\Carrier::whereIn('id', [2, 3, 4, 5, 6, 7, 11])->pluck('name', 'easypost');

        return view('tracking.create_tracker', compact('carriers'));
    }

    /**
     * Show tracking events for a shipment.
     *
     * @param
     * @return
     */
    public function sendTrackerRequest(Request $request)
    {
        $this->validate($request, ['tracking_code' => 'required', 'carrier' => 'required']);

        $shipment = Shipment::whereConsignmentNumber($request->tracking_code)->orWhere('carrier_tracking_number', $request->tracking_code)->first();

        if (! $shipment) {
            return back()->withInput()->withErrors(['tracking_code' => 'Invalid tracking code!']);
        }

        $easypostApiKey = 'mmJ7I06Yq6Ogg2soH5RncQ';
        $trackingCode = $request->tracking_code;

        if (\App::environment('local')) {
            $easypostApiKey = 'QLLbKAY0onVCjjFut30VCA';
            $trackingCode = 'EZ7000000007';
        }

        try {
            \EasyPost\EasyPost::setApiKey($easypostApiKey);
            $tracker = \EasyPost\Tracker::create(['tracking_code' => $trackingCode, 'carrier' => $request->carrier]);
        } catch (\EasyPost\Error $ex) {
            flash()->success('Failed!', $ex, true);

            return back();
        }

        flash()->success('Tracker Created!', 'Easypost are now tracking shipment '.$trackingCode, true);

        return back();
    }

    /**
     * Send request to easypost to push events to the webhook.
     *
     * @param type $id
     */
    public function requestPushToWebhook(Request $request)
    {
        if ($request->consignment) {
            $shipments = Shipment::where('consignment_number', $request->consignment)->whereReceived(1)->get();
        } else {
            $shipments = Shipment::orderBy('ship_date', 'DESC')
                ->whereReceived(1)
                ->whereIn('status_id', [8, 9, 10, 11, 12, 17])
                ->limit(10)
                ->get();
        }

        echo $shipments->count().' shipments loaded<br>';

        foreach ($shipments as $shipment) {
            $tracking = \App\Models\Tracking::whereShipmentId($shipment->id)->whereSource('easypost')->first();

            if ($tracking) {
                echo $shipment->consignment_number.' / '.$tracking->tracker_id.'<br>';

                $ch = curl_init('https://api.easypost.com/v2/events');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['result_id' => $tracking->tracker_id]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERNAME, 'mmJ7I06Yq6Ogg2soH5RncQ');

                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: '.strlen(json_encode(['result_id' => $tracking->tracker_id])), ]
                );

                $res = curl_exec($ch);

                if ($res) {
                    echo 'success';
                }

                flush();
                ob_flush();
            }
        }
    }
}
