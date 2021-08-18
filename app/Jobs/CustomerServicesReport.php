<?php

namespace App\Jobs;

use \App\Models\Company;
use \App\Models\Service;

use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CustomerServicesReport implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $timeout = 999;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $recipient = Auth::user()->email;
        $data = '';
        $testing = false;
        if ($testing) {
            $companies = Company::whereIn('id', ['57','774'])->where('enabled', '1')->where('testing', '0')->where('depot_id', '1')->orderBy('company_name')->get(); // Testing
        } else {
            $companies = Company::where('enabled', '1')->where('testing', '0')->where('depot_id', '1')->orderBy('company_name')->get(); // Testing
        }
        $data = '"id","Company","IFS Service",IFS Service Name","Carrier Service Name","Carrier"'."\n";
        foreach ($companies as $company) {
            $services = $company->getServices();
            foreach ($services as $service) {
                $data .= $company->id;
                $data .= ','.$company->company_name;
                $data .= ','.$service->code;
                $data .= ','.$service->name;
                $data .= ','.$service->carrier_name;
                $data .= ','.$service->carrier->code."\n";
            }
        }

        Mail::to($recipient)->send(new \App\Mail\CustomerServicesReport($data));
    }
}
