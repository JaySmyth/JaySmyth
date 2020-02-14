<?php

namespace App\Jobs;

use App\Shipment;
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

    protected $trackingCode;
    protected $carrier;
    protected $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trackingCode, $carrier)
    {
        $this->trackingCode = $trackingCode;
        $this->carrier = $carrier;
        $this->key = config('services.easypost.key');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->carrier == '***') {
            return;
        }

        // Update unused field to show that shipment is being tracked by easypost
        $shipment = Shipment::where('carrier_tracking_number', $this->trackingCode)->orderBy('id', 'desc')->first();

        if ($shipment) {
            $shipment->external_tracking_url = 'easypost';
            $shipment->save();
        }

        try {
            \EasyPost\EasyPost::setApiKey($this->key);
            \EasyPost\Tracker::create(['tracking_code' => $this->trackingCode, 'carrier' => $this->carrier]);
        } catch (\EasyPost\Error $ex) {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker ('.$this->trackingCode.' - '.$this->carrier.')', $ex));
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
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker ('.$this->trackingCode.' - '.$this->carrier.')', $exception));
    }
}
