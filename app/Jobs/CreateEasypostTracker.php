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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trackingCode, $carrier)
    {
        $this->trackingCode = $trackingCode;
        $this->carrier = $carrier;
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

        if (env('APP_ENV') == "production") {
            \EasyPost\EasyPost::setApiKey(env('EASYPOST_KEY'));
            \EasyPost\Tracker::create(array('tracking_code' => $this->trackingCode, 'carrier' => $this->carrier));
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
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Create Easypost Tracker (' . $this->trackingCode . ' - ' . $this->carrier . ')', $exception));
    }

}
