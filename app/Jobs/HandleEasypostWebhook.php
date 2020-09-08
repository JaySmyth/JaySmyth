<?php

namespace App\Jobs;

use App\Models\ProblemEvent;
use App\Models\Tracking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class HandleEasypostWebhook implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $shipment;
    protected $result;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($result, $shipment)
    {
        $this->result = $result;
        $this->shipment = $shipment;
    }

    /**
     * Execute the job. Loop through each event and create a new tracking record, then
     * perform any necessary actions for the given event type.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->result['tracking_details'] as $event) {
            $event = $this->tidyUpEvent($event);

            /*
             * Ignore tracking events prior to date shipment was created.
             * This is to combat situations where easypost have sent old tracking
             * for duplicate trackers on their system.
             *
             */

            $createdAt = $this->shipment->created_at->subHours(2);

            $eventsToIgnore = ['Shipment information sent to FedEx', 'Order Processed: Ready for UPS'];

            if ($event['datetime'] < $createdAt || in_array($event['message'], $eventsToIgnore)) {
                continue;
            }

            $tracking = Tracking::firstOrCreate(['message' => $event['message'], 'status' => $event['status'], 'datetime' => $event['datetime'], 'shipment_id' => $this->shipment->id])->update($event);

            if ($tracking) {
                $this->processEvent($event);
            }
        }
    }

    /**
     * Format the event.
     *
     * @param array $event
     * @return type
     */
    private function tidyUpEvent($event)
    {
        $event['status_detail'] = $event['status_detail'];
        $event['city'] = $event['tracking_location']['city'];
        $event['state'] = $event['tracking_location']['state'];
        $event['country_code'] = $this->getCountryCode($event['tracking_location']['country'], $event['tracking_location']['city'], $event['message']);
        $event['postcode'] = $event['tracking_location']['zip'];
        $event['local_datetime'] = Carbon::parse($event['datetime']);
        $event['datetime'] = Carbon::parse($event['datetime']);
        $event['carrier'] = $this->result['carrier'];
        $event['tracker_id'] = $this->result['id'];
        $event['source'] = 'easypost';
        $event['estimated_delivery_date'] = ($this->result['est_delivery_date']) ? Carbon::parse($this->result['est_delivery_date']) : null;
        $event['local_estimated_delivery_date'] = ($this->result['est_delivery_date']) ? Carbon::parse($this->result['est_delivery_date']) : null;
        $event['user_id'] = 0;
        $event['message'] = ($event['message']) ? $event['message'] : $event['status'];

        return $event;
    }

    /**
     * Set to received using tracking event.
     *
     * @param type $event
     */
    private function ensureShipmentReceived($event)
    {
        $ignore = ['pre_transit', 'cancelled', 'unknown', 'error', 'failure'];

        if (! in_array($event['status'], $ignore)) {

            // Set to received
            if (! $this->shipment->received) {
                $this->shipment->setReceived($event['datetime'], 0, true);
            }

            // Ensure hold flag is removed
            if ($this->shipment->on_hold) {
                $this->shipment->on_hold = false;
                $this->shipment->save();
            }

            // Add shipment to last manifest closed out.
            if (! is_numeric($this->shipment->manifest_id)) {
                //$this->shipment->addToLastManifest();
            }
        }
    }

    /**
     * Perform any necessary actions based upon the current event status.
     *
     * @param type $event
     */
    private function processEvent($event)
    {
        $sentProblem = false;

        // Set shipment to received - catches scans missed by IFS
        $this->ensureShipmentReceived($event);

        switch ($event['status']) {

            case 'pre_transit':
                // do nothing
                break;

            case 'in_transit':
                if (stristr($event['message'], 'Shipment on hold') || stristr($event['message'], 'Shipment held') || stristr($event['message'], 'TO BE HELD')) {
                    $this->shipment->setStatus('on_hold', 0, false, false);
                } else {
                    $this->shipment->setStatus($event['status'], 0, false, false);
                }

                break;

            case 'out_for_delivery':
            case 'cancelled':
                $this->shipment->setStatus($event['status'], 0, false, false);

                if ($event['message'] == 'On carrier vehicle for delivery' && $event['city'] == 'ANTRIM GB') {
                    $this->shipment->setStatus('return_to_sender', 0, false, false);
                    $this->shipment->alertProblem('Shipment returned to sender', ['s', 'b', 'o', 'd']);
                    $sentProblem = true;
                }

                break;

            case 'delivered':
                $this->shipment->setDelivered($event['datetime'], $this->result['signed_by']);
                break;

            case 'unknown':
            case 'error':
            case 'failure':
            case 'return_to_sender':
            case 'available_for_pickup':

                if ($event['status'] != 'failure' && ! stristr($event['message'], 'clearance delay')) {
                    $this->shipment->setStatus($event['status'], 0, false, false);
                }

                $this->shipment->alertProblem($event['message'], ['s', 'b', 'o', 'd']);
                $sentProblem = true;
                break;

            default:
                // unknown status
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Unknown tracking status ('.$event['status'].')', $event['tracker_id']));
                break;
        }

        if (! $sentProblem) {
            $this->alertProblem($event['message']);
        }
    }

    /**
     * Check if we have received a "problem" event that we need to send email for.
     *
     * @param type $message
     */
    private function alertProblem($message)
    {
        $problemEvents = ProblemEvent::all();

        foreach ($problemEvents as $problemEvent) {
            $relevance = explode(',', $problemEvent->relevance);

            if (stristr($message, $problemEvent->event)) {
                $this->shipment->alertProblem($problemEvent->event, $relevance);
            }
        }
    }

    /**
     * Ensure we get a valid country code for the tracking event. Necessary as the country
     * field may be null or in various formats.
     *
     * @param type $country
     * @param type $city
     * @param type $message
     */
    private function getCountryCode($country, $city, $message)
    {
        $countryCode = getCountryCode($country);

        if (! $countryCode && substr($city, -3) == ' GB') {
            $countryCode = 'GB';
        }

        return $countryCode;
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Handle Easypost Webhook ('.$this->shipment->carrier_tracking_number.')', $exception));
    }
}
