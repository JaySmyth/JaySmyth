<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
            return null;
        }

        try {
            \EasyPost\EasyPost::setApiKey($this->key);
            \EasyPost\Tracker::create(array('tracking_code' => $this->trackingCode, 'carrier' => $this->carrier));
        } catch (\EasyPost\Error $ex) {
            if (!App::environment('local')) {
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker (' . $this->trackingCode . ' - ' . $this->carrier . ')', $ex));
            }

        }
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed($exception)
    {
        if (!App::environment('local')) {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker (' . $this->trackingCode . ' - ' . $this->carrier . ')', $exception));
        }
    }

}
