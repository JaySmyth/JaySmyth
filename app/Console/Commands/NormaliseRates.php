<?php

/**
 * ***********************************
 *        Program not complete
 *
 *            Do not use
 *
 * ***********************************
 */

namespace App\Console\Commands;

use App\Company;
use App\Rate;
use App\CompanyRates;
use Illuminate\Console\Command;

class NormaliseRates extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:normalise-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build any company-rates discounts into rate.';
    protected $checkDate;
    protected $shipments = [];
    protected $carrierApi;
    protected $apiShipment;
    protected $apiController;
    protected $input;

    /**
     * Number of errors.
     *
     * @var string
     */
    protected $errors = 0;
    protected $correct = 0;
    protected $cnt = 0;

    /**
     * Array of error messages.
     *
     * @var string
     */
    protected $errorMessages = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $errorMessage = [];

        // Get all company rates where rate is expressed as a percentage
        $this->companyRates = CompanyRates::where('discount', '<>', '0.00')
            ->get();
        $this->cnt = $this->companyRates->count();
        foreach ($this->companyRates as $companyRate) {

            // Check Service is still valid for Company
            $company = Company::find($companyRate->company_id);

            $service = $company->getServices()->where('pivot.service_id', $companyRate->service_id)->first();
            if ($service) {
                $rate = Rate::find($companyRate->rate_id);

                // Download rate applying discount %
                $requiredRate = $rate->downloadCompanyRate($company, $service, $companyRate->discount, '', false);

                // Upload file inclusive of discount
                // return $rate->processRateUpload($company->id, $service->id, $rate->id, $requiredRate, $effectiveDate);

                // Remove the discount percentage as now built into rate
                // $companyRate->discount = 0;
                // $companyRate->save();
                $this->correct++;
            } else {
                $errorMessage = "CompanyRate Id : ".$companyRate->id."Company Id : ".$companyRate->company_id." Service_id ".$companyRate->service_id." not valid\n";
                $this->errorMessages[] = $errorMessage;
            }

            $this->displayResult($errorMessage);
        }

        $this->displaySummary($this->cnt);
    }

    public function displayResult($errorMessage)
    {
        if ($this->errorMessages == []) {

            $this->correct++;
            echo '.';
        } else {

            $this->errors++;
            echo 'x';
        }
    }

    public function displaySummary()
    {

        echo "\n".$this->cnt." Company Rates Selected, "
            .$this->correct." Rates Normalised, "
            ."$this->errors Rates not used\n\n";

        if ($this->errors > 0) {
            foreach ($this->errorMessages as $error) {
                echo $error;
            }
        }
    }

}
