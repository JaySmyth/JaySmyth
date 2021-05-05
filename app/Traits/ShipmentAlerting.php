<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

trait ShipmentAlerting
{
    /*
     * An array of status codes to alert for
     */

    protected $genericAlerts = ['despatched', 'out_for_delivery', 'delivered', 'cancelled'];

    /**
     * Send a generic shipment alert and update the alerts table.
     *
     * @param string $statusCode a valid email e.g. collected, delivered, cancelled
     */
    public function alertGeneric($statusCode)
    {
        // ensure lower case
        $statusCode = strtolower($statusCode);

        if (! in_array($statusCode, $this->genericAlerts)) {
            return false;
        }

        // define the fields that we want to update
        $fieldSent = $statusCode.'_sent';
        $fieldSentAt = $fieldSent.'_at';

        foreach ($this->alerts as $alert) {
            if ($alert->$statusCode && ! $alert->$fieldSent) {
                Mail::to($alert->email)->queue(new \App\Mail\ShippingAlertGeneric($this, $statusCode));

                $alert->$fieldSent = true;
                $alert->$fieldSentAt = Carbon::now();
                $alert->update();
            }
        }
    }

    /**
     * Send problem email and update alerts table.
     *
     * @param string $problemEvent problem event message
     * @param array $relevance (s)ender (r)ecipient (b)roker (o)ther (d)epartment
     */
    public function alertProblem($problemEvent, $relevance)
    {
        foreach ($this->alerts as $alert) {
            if ($alert->problems && in_array($alert->type, $relevance) && ! stristr($alert->problems_sent, $problemEvent)) {
                Mail::to($alert->email)->queue(new \App\Mail\ShippingAlertProblem($this, $problemEvent));

                $alert->problems_sent .= '|'.$problemEvent;
                $alert->update();
            }
        }
    }

    /**
     * Insert a record to the alerts table for the associated department.
     *
     * @param bool $allAlerts
     * @return type
     */
    public function setDepartmentAlerts()
    {
        // Default email to catch shipments with no department email
        $email = 'alerts.international@antrim.ifsgroup.com';

        if (filter_var($this->department->email, FILTER_VALIDATE_EMAIL)) {
            $email = $this->department->email;
        }

        // Problems only
        return $this->alerts()->create([
            'email' => $email,
            'type' => 'd',
            'problems' => 1,
        ]);
    }

    /**
     * Send IFS notifications (notify IFS staff).
     */
    public function sendIfsNotifications()
    {
        $this->sendArrangePickup();
        $this->sendAirFreightBooked();
        $this->sendWarning();
        $this->sendHazardousBooked();
        $this->sendInsuranceRequested();
        $this->sendLossMaking();
        $this->sendShipReason();
    }

    /**
     * Notify courier that carrier pickup must be arranged.
     */
    private function sendArrangePickup()
    {
        // If sender postcode not "BT", alert the department. This is required so that any mainland pickups can be arranged etc.
        if (! $this->originatesFromBtPostcode() && strtoupper($this->sender_country_code != 'US')  && !in_array($this->company_id, [1015, 1102, 1103])) {
            Mail::to(['courier@antrim.ifsgroup.com','courieruk@antrim.ifsgroup.com'])->queue(new \App\Mail\ArrangePickup($this));
        }
    }

    /**
     * Air freight shipment booked - notify air exports.
     */
    private function sendAirFreightBooked()
    {
        if (strtoupper($this->service->scs_job_route) == 'XFF') {
            Mail::to('airexports@antrim.ifsgroup.com')->queue(new \App\Mail\AirFreightBooked($this));
        }
    }

    /**
     * Check for high value or problem countries.
     */
    private function sendWarning()
    {
        $message = '';
        if ($this->getGbpCustomsValue() > 25000 && in_array($this->recipient_country_code, ['IR'])) {
            $message = 'both';
        } else {
            if ($this->getGbpCustomsValue() > 25000) {
                $message = 'value';
            }

            if (in_array($this->recipient_country_code, ['IR'])) {
                $message = 'country';
            }
        }

        if ($message > '') {
            Mail::to('courier@antrim.ifsgroup.com')->cc(['ghanna@antrim.ifsgroup.com', 'sfleck@antrim.ifsgroup.com'])->queue(new \App\Mail\ShipmentWarning($this, $message));
        }
    }

    /**
     * Check for hazardous or dry ice shipments.
     */
    private function sendHazardousBooked()
    {
        $batteries = (! empty($this->lithium_batteries) && $this->lithium_batteries > 0) ? true : false;

        if (is_numeric($this->hazardous) || strtoupper($this->hazardous) == 'E' || $this->dry_ice_flag || $batteries) {
            Mail::to('courier@antrim.ifsgroup.com')->queue(new \App\Mail\ShipmentWarning($this, 'hazardous'));
        }
    }

    /**
     * Check for shipment insurance.
     */
    private function sendInsuranceRequested()
    {
        if ($this->insurance_value > 0) {
            if ($this->isUkDomestic()) {
                $recipient = 'courieruk@antrim.ifsgroup.com';
            } else {
                $recipient = 'courier@antrim.ifsgroup.com';
            }

            Mail::to($recipient)->cc(['it@antrim.ifsgroup.com', 'lnevin@antrim.ifsgroup.com'])->queue(new \App\Mail\ShipmentWarning($this, 'insurance'));
        }
    }

    /**
     * Check for loss making shipment.
     */
    private function sendLossMaking()
    {
        if ($this->shipping_cost > $this->shipping_charge && ! in_array($this->company_id, [113])) {
            if ($this->isUkDomestic()) {
                $recipient = 'gmcnicholl@antrim.ifsgroup.com';
                $ccEmails = ['aplatt@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com', 'sanderton@antrim.ifsgroup.com', 'dclarke@antrim.ifsgroup.com'];
            } else {
                $recipient = 'aplatt@antrim.ifsgroup.com';
                $ccEmails = ['it@antrim.ifsgroup.com', 'sanderton@antrim.ifsgroup.com', 'dclarke@antrim.ifsgroup.com'];
            }

            Mail::to($recipient)->cc($ccEmails)->queue(new \App\Mail\ShipmentWarning($this, 'loss'));
        }
    }

    /**
     * Non standard ship reason.
     */
    private function sendShipReason()
    {
        if (in_array($this->ship_reason, ['temp', 'repair'])) {                 //  && ! $this->isWithinEu()
            Mail::to('courier@antrim.ifsgroup.com')->queue(new \App\Mail\ShipmentWarning($this, 'ship_reason'));
        }
    }

    /**
     * Test the email that may be generated against a shipment.
     *
     * @param type $email
     */
    public function sendTestEmails($email)
    {
        foreach ($this->genericAlerts as $statusCode) {
            Mail::to($email)->queue(new \App\Mail\ShippingAlertGeneric($this, $statusCode));
        }

        Mail::to($email)->queue(new \App\Mail\ShippingAlertProblem($this, 'Some random problem event'));
    }

    /**
     * Air freight shipment booked with airline.
     */
    private function sendBookedWithAirline()
    {
        // Only do this for CDE at present
        if ($this->company_id == 314) {
            if (filter_var($this->sender_email, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $this->sender_email;
            } else {
                $recipientEmail = 'airexports@antrim.ifsgroup.com';
            }

            Mail::to($recipientEmail)->cc('airexports@antrim.ifsgroup.com')->queue(new \App\Mail\BookedWithAirline($this));
        }
    }
}
