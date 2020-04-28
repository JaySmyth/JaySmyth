<?php

namespace App\Tracking;

use App\Models\ProblemEvent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

abstract class Tracking
{
    protected $shipment;
    protected $trackingNumber;

    /**
     * Constructor.
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
        $this->trackingNumber = $shipment->carrier_tracking_number;
    }

    /**
     * Request tracking events from carrier and update shipment accordingly.
     *
     * @return bool
     */
    public function update()
    {
        $events = $this->getEvents();

        if (is_array($events)) {
            foreach ($events as $event) {
                if (! $this->ignoreEvent($event)) {
                    $tracking = \App\Models\Tracking::firstOrCreate(['message' => $event['message'], 'status' => $event['status'], 'shipment_id' => $this->shipment->id])->update($event);

                    if ($tracking) {
                        $this->processEvent($event);
                    }
                }
            }
        }

        return true;
    }

    abstract protected function getEvents();

    /**
     * Determine if an event should be processed.
     *
     * @param $event
     * @return bool
     */
    protected function ignoreEvent($event)
    {
        if ($event['datetime'] < $this->shipment->created_at->subHours(2) || in_array($event['message'], $this->ignore())) {
            return true;
        }

        return false;
    }

    abstract protected function ignore();

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

        // Update the shipment status (ignore pre-transit and delivered)
        if (! in_array($event['status'], ['pre_transit', 'delivered', 'failure'])) {
            $this->shipment->setStatus($event['status'], 0, false, false);
        }

        switch ($event['status']) {

            case 'in_transit':
            case 'pre_transit':
            case 'out_for_delivery':
                // do nothing
                break;

            case 'return_to_sender':
                $this->shipment->alertProblem('Shipment returned to sender', ['s', 'b', 'o', 'd']);
                $sentProblem = true;
                break;

            case 'error':
            case 'failure':
            case 'unknown':
            case 'available_for_pickup':
                $this->shipment->alertProblem($event['message'], ['s', 'b', 'o', 'd']);
                $sentProblem = true;
                break;

            case 'delivered':
                $this->shipment->setDelivered($event['datetime'], $event['signed_by']);
                break;

            default:
                // unknown status
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Unknown tracking status ('.$event['status'].')', $this->trackingNumber));
                break;
        }

        if (! $sentProblem) {
            $this->alertProblem($event['message']);
        }
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

    abstract protected function formatResponse($response);

    abstract protected function buildRequest();
}
