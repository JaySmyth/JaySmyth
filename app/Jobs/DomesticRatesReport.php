<?php

namespace App\Jobs;

use \App\Models\Company;
use \App\Models\Rate;
use \App\Models\Service;

use DB;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DomesticRatesReport implements ShouldQueue
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
        // Get Data
        $rateView = [];
        $recipient = 'aplatt@antrim.ifsgroup.com';
        $service = Service::find("53");
        $shipDate = Carbon::today()->toDateString('Y-m-d');

        // $companies = Company::where('enabled', '1')->where('testing','0')->where('depot_id','1')->orderBy('company_name')->get();
        $companies = Company::whereIn('id', ['57','774'])->get();
        foreach ($companies as $company) {
            $rateInfo = $company->salesRateForService($service->id);
            if (isset($rateInfo['id']) && $rateInfo['id'] > 0) {
                $discount = (isset($rateInfo->discount)) ? $rateInfo->discount : 0;
                $rate = Rate::find($rateInfo['id']);
                if ($rate) {
                    if ($rate->model == 'domestic') {
                        $rateView[$company->id] = $rate->getRateView($company, '', $discount, $shipDate, 'data');
                    } else {
                        $rateView[$company->id] = $rate->getRateView($company, $service, $discount, $shipDate, 'data');
                    }
                }
            }
        }

        foreach ($rateView as $companyId => $companyView) {
            $company = Company::find($companyId);
            $data = '';
            foreach ($companyView as $zone) {
                $data .= $company->company_name.
                ','.$zone['service'].
                ','.$zone['packaging_code'].
                ','.$zone['area'].
                ','.$zone['first'].
                ','.$zone['others'].
                ','.$zone['notional_weight'].
                ','.$zone['notional']."\n";
            }
        }

        Mail::to($recipient)->cc('gmcbroom@antrim.ifsgroup.com')->send(new \App\Mail\CustomerRatesReport($data));
    }
}
