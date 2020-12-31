<?php

namespace App\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TransendOrderImport implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;
    protected $userId = 'webservice';
    protected $password = 'Y7botH8XzeJMdNgshE1JMLfnWiP+0dGTvRTjRpGv9Vs=';
    protected $companyId = 75;
    protected $uri = 'http://tsapp.ifsgroup.com/Order/importorder_json';
    protected $transportJob;
    protected $actionIndicator;
    protected $shipment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transportJob, $actionIndicator = 'A')
    {
        $this->transportJob = $transportJob;
        $this->actionIndicator = $actionIndicator;
        $this->shipment = ($this->transportJob->shipment) ? $this->transportJob->shipment : false;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        $jsonData = json_encode($this->buildRequest(), JSON_HEX_AMP | JSON_HEX_APOS);

        // Remove pipe symbol (transend doesn't like them)
        $jsonData = str_replace('|', '', $jsonData);

        if (! $jsonData) {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Transend Order Import ('.$this->transportJob->number.')', 'Failed to json encode data'));
            exit();
        }

        if ($this->getDepot() == 'TEST') {
            echo "\n\n".$jsonData."\n\n";
        }

        if ($this->transportJob->goods_description == 'TEST') {
            Mail::to('dshannon@antrim.ifsgroup.com')->send(new \App\Mail\GenericError($this->transportJob->number.' JSON', $jsonData));
        }

        try {

            // Send the json to transend
            $response = $client->post($this->uri, ['body' => $jsonData]);

            // Get transend response (some clean up required as response is not in a standard format)
            $reply = json_decode($this->clean($response->getBody()->getContents()), true);

            // Order imported to transend successfully - update the tranport job to sent
            if (isset($reply['RequestStatus']) && $reply['RequestStatus'] == 'OK') {
                $this->transportJob->setToSent();
                $this->transportJob->log('Transend Import Successful', ($this->actionIndicator == 'D') ? 'Cancel Job' : null, $reply);
            } else {
                // Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Transend Order Import Failed ('.$this->transportJob->number.')', $reply['RequestError'].' - JSON: '.$jsonData));
            }
        } catch (GuzzleException $exc) {
            if ($exc->hasResponse()) {
                Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Transend Order Import GuzzleException ('.$this->transportJob->number.')', Psr7\str($exc->getResponse()).' - JSON: '.$jsonData));
            }
        }
    }

    /**
     * Build the request to send to transend.
     */
    private function buildRequest()
    {
        return [
            'UserID' => $this->userId,
            'Password' => $this->password,
            'CompanyID' => $this->companyId,
            'AutoCreateSubAccounts' => true,
            'DebugSimulateError' => false,
            'SaveOrdersInDB' => true,
            'Orders' => [
                [
                    'ActionIndicator' => $this->actionIndicator,
                    'StartDepotCode' => $this->getDepot(),
                    'Account' => [
                        'AccountCode' => $this->getAccountCode(),
                        'AccountTypeCode' => ($this->transportJob->type == 'd') ? 'DELIVERY' : 'COLLECTION',
                        'DepotCode' => $this->getDepot(),
                        'AccountName' => $this->getAccountName(),
                        'Address' => $this->getAddress(),
                        'ContactDetails' => $this->getContactDetail(),
                    ],
                    'StartDepotCode' => $this->getDepot(),
                    'TransportOrderRef' => $this->transportJob->number.strtoupper($this->transportJob->type).$this->transportJob->attempts,
                    'OrderDate' => $this->transportJob->date_requested->format('Y-m-d\TH:i:s'),
                    'DeliveryDate' => $this->transportJob->date_requested->format('Y-m-d\TH:i:s'),
                    'PlannedArriveTime' => $this->getPlannedTime(),
                    'PlannedDepartTime' => $this->getPlannedTime(15),
                    'SpecialInstructions' => $this->transportJob->instructions,
                    'TextField1' => $this->transportJob->transend_route,
                    'TextField2' => ($this->shipment) ? strtoupper($this->shipment->service->code) : $this->transportJob->transend_route,
                    'OrderJobs' => [
                        [
                            'JobTypeCode' => $this->getJobTypeCode(),
                            'JobRef1' => $this->transportJob->number,
                            'JobRef2' => substr($this->transportJob->reference, 0, 40),
                            'JobRef3' => ($this->shipment) ? $this->shipment->shipment_reference : $this->transportJob->scs_job_number,
                            'JobRef4' => $this->getAccountCode(),
                            'Sequence' => 1,
                            'ExpectedPaymentAmount' => $this->transportJob->cash_on_delivery,
                            'OrderJobDetails' => $this->getJobDetails(),
                        ],
                    ],
                    'Attributes' => [
                        [
                            'Code' => 'ROUTENO',
                            'Value' => $this->transportJob->transend_route,
                        ],
                        [
                            'Code' => 'SERVICE',
                            'Value' => ($this->shipment) ? strtoupper($this->shipment->service->code) : $this->transportJob->transend_route,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get depot.
     *
     * @return string
     */
    protected function getDepot()
    {
        if (\App::environment('local')) {
            return 'TEST';
        }

        return 'IFS';
    }

    /**
     * Get account code.
     *
     * @return type
     */
    private function getAccountCode()
    {
        // Already set
        if (strlen($this->transportJob->transend_account_code) > 0) {
            return $this->transportJob->transend_account_code;
        }

        // Deliveries, use company name and postcode
        if ($this->transportJob->type == 'd') {
            return strtoupper(substr($this->transportJob->to_company_name, 0, 3).'00'.preg_replace('/\s+/', '', $this->transportJob->to_postcode).'-001');
        }

        // Collections with known SCS code
        if ($this->transportJob->scs_company_code) {
            return $this->transportJob->scs_company_code.'-002';
        }

        // Collections where SCS code isn't known, use company name and postcode
        return strtoupper(substr($this->transportJob->from_company_name, 0, 3).'00'.preg_replace('/\s+/', '', $this->transportJob->from_postcode).'-002');
    }

    /**
     * Get account name.
     *
     * @return string
     */
    private function getAccountName()
    {
        if ($this->transportJob->type == 'd') {
            return strtoupper(substr($this->transportJob->to_company_name, 0, 3). 00 .$this->transportJob->to_postcode);
        }

        return ($this->shipment) ? $this->shipment->company->company_name : strtoupper(substr($this->transportJob->from_company_name, 0, 3). 00 .$this->transportJob->from_postcode);
    }

    /**
     * Address element.
     *
     * @param type $order
     */
    private function getAddress()
    {
        if ($this->transportJob->type == 'd') {
            return [
                'Address1' => $this->transportJob->to_company_name,
                'Address2' => $this->transportJob->to_address1,
                'Address3' => $this->transportJob->to_address2,
                'Address4' => $this->transportJob->to_address3,
                'Address5' => $this->transportJob->to_city,
                'Address6' => $this->transportJob->to_state,
                'PostCode' => ($this->transportJob->to_postcode) ? $this->transportJob->to_postcode : 'XX',
            ];
        }

        return [
            'Address1' => $this->transportJob->from_company_name,
            'Address2' => $this->transportJob->from_address1,
            'Address3' => $this->transportJob->from_address2,
            'Address4' => $this->transportJob->from_address3,
            'Address5' => $this->transportJob->from_city,
            'Address6' => $this->transportJob->from_state,
            'PostCode' => ($this->transportJob->from_postcode) ? $this->transportJob->from_postcode : 'XX',
        ];
    }

    /**
     * Get contact element.
     *
     * @param type $order
     */
    private function getContactDetail()
    {
        if ($this->transportJob->type == 'd') {
            return [
                'ContactEmailAddress' => $this->transportJob->to_email,
                'ContactName' => $this->transportJob->to_name,
                'ContactNumber' => $this->transportJob->to_telephone,
            ];
        }

        return [
            'ContactEmailAddress' => $this->transportJob->from_email,
            'ContactName' => $this->transportJob->from_name,
            'ContactNumber' => $this->transportJob->from_telephone,
        ];
    }

    /**
     * Planned time to arrive/depart.
     *
     * @param type $minutes
     * @return string
     */
    protected function getPlannedTime($minutes = false)
    {
        $time = '12:00';

        if ($this->transportJob->type == 'c') {
            if (! empty($this->transportJob->routing['collection_time'])) {
                $time = $this->transportJob->routing['collection_time'];
            }
        } else {
            if (! empty($this->transportJob->routing['delivery_time'])) {
                $time = $this->transportJob->routing['delivery_time'];
            }
        }

        $time = Carbon::parse(date('Y-m-d').' '.$time);

        if ($minutes) {
            return $time->addMinutes($minutes)->format('Y-m-d H:i:s');
        }

        return $time->format('Y-m-d H:i:s');
    }

    /**
     * Get Transend job type code.
     *
     * @return string
     */
    protected function getJobTypeCode()
    {
        // Courier job, use the service code with appendage
        if (! empty($this->shipment->service->code)) {
            return strtoupper($this->shipment->service->code.$this->transportJob->type);
        }

        // For non courier jobs, use the transend code defined in the departments table with appendage
        if (! empty($this->transportJob->department->transend_code)) {
            return strtoupper($this->transportJob->department->transend_code.$this->transportJob->type);
        }

        return 'unknown';
    }

    /**
     * Add job details.
     *
     * @return collection
     */
    private function getJobDetails()
    {
        $details = [];

        if ($this->shipment) {
            foreach ($this->shipment->packages as $package) {
                $contents = $package->getContents();

                $details[] = [
                    'LineNumber' => $package->index,
                    'Barcode' => ($package->barcode) ? $package->barcode : $package->index,
                    'Description' => substr($contents['description'], 0, 40),
                    'OrderedQty' => 1,
                    'OriginalDespatchQty' => 1,
                    'SkuWeight' => $package->weight,
                    'SkuCube' => $package->volumetric_weight,
                ];
            }

            return $details;
        }

        $details[] = [
            'LineNumber' => 1,
            'Barcode' => 1,
            'Description' => substr(trim($this->transportJob->goods_description), 0, 40),
            'OrderedQty' => $this->transportJob->pieces,
            'OriginalDespatchQty' => $this->transportJob->pieces,
            'SkuWeight' => $this->getTransportJobWeight('weight'),
            'SkuCube' => $this->getTransportJobWeight('volumetric_weight'),
        ];

        return $details;
    }

    /**
     * For non courier jobs, calc peice weight.
     *
     * @return float|int
     */
    private function getTransportJobWeight($field)
    {
        if ($this->transportJob->$field > 0 && $this->transportJob->pieces > 1) {
            return $this->transportJob->$field / $this->transportJob->pieces;
        }

        return $this->transportJob->$field;
    }

    /**
     * Clean up malformed Transend JSON.
     *
     * @param type $string
     * @return string
     */
    private function clean($string)
    {
        $pos = strpos($string, '{');
        $string = substr($string, $pos);
        $string = str_replace("\r\n", '', $string);
        $pos = strrpos($string, '}');
        $string = substr($string, 0, $pos + 1);

        return $string;
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed($exception)
    {
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Transend Order Import ('.$this->transportJob->number.')', $exception));
    }
}
