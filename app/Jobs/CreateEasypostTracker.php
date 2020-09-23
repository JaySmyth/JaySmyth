<?php

namespace App\Jobs;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CreateEasypostTracker implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $shipment;
    protected $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shipmentId)
    {
        $this->shipment = Shipment::find($shipmentId);
        $this->key = config('services.easypost.key');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->shipment->carrier->easypost == '***') {
            return;
        }

        try {
            \EasyPost\EasyPost::setApiKey($this->key);
            \EasyPost\Tracker::create(['tracking_code' => $this->shipment->carrier_consignment_number, 'carrier' => $this->shipment->carrier->easypost]);

            $this->shipment->tracker_created = true;
            $this->shipment->save();

        } catch (\EasyPost\Error $ex) {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker ('.$this->shipment->carrier_consignment_number.' - '.$this->shipment->carrier->easypost.')', $ex));
        }

    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker ('.$this->shipment->carrier_consignment_number.' - '.$this->shipment->carrier->easypost.')', $exception));
    }
}
