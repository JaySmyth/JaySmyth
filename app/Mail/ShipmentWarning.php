<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipmentWarning extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $shipment;
    protected $warningSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shipment, $warningSubject)
    {
        $this->shipment = $shipment;
        $this->warningSubject = $warningSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '';
        $valueClass = '';
        $countryClass = '';
        switch ($this->warningSubject) {
            case 'value':
                $subject = 'High customs value detected (over £25,000)';
                $valueClass = 'error';
                break;

            case 'country':
                $subject = 'Problem country detected';
                $countryClass = 'error';
                break;

            case 'hazardous':
                $subject = 'Hazardous/Dry Ice Shipment/Lithium Batteries detected';
                break;

            case 'insurance':
                $subject = 'Insured shipment requested';
                break;

            case 'loss':
                $subject = 'Loss Making Shipment';
                break;

            case 'reset':
                $subject = 'Shipment Reset';
                break;

            case 'ship_reason':
                $subject = 'Non standard ship reason detected ('.$this->shipment->ship_reason.')';
                break;

            default:
                $subject = 'High customs value (over £25,000) and problem country detected';
                $valueClass = 'error';
                $countryClass = 'error';
                break;
        }

        return $this->view('emails.shipments.warning')
                        ->subject($subject.' - '.$this->shipment->consignment_number)
                        ->with(['shipment' => $this->shipment,
                            'subject' => $subject,
                            'valueClass' => $valueClass,
                            'countryClass' => $countryClass,
        ]);
    }
}
