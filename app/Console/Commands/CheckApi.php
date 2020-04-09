<?php

namespace App\Console\Commands;

use App\Http\Controllers\APIController;
use App\Models\CarrierAPI\APIShipment;
use App\Models\CarrierAPI\CarrierAPI;
use App\Models\Company;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Console\Command;

class CheckApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-api {checkDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check API by resending a number of JSON queries';
    protected $checkDate;
    protected $shipments = [];
    protected $carrierApi;
    protected $apiShipment;
    protected $apiController;
    protected $input;

    /**
     * Number of correctly priced shipments.
     *
     * @var string
     */
    protected $correct = 0;

    /**
     * Number of correctly priced shipments.
     *
     * @var string
     */
    protected $legacy = 0;

    /**
     * Number of incorrectly priced shipments.
     *
     * @var string
     */
    protected $errors = 0;

    /**
     * Array of error messages.
     *
     * @var string
     */
    protected $errorMessages = [];

    /**
     * Shipment Counter.
     *
     * @var string
     */
    protected $cnt = 0;

    /**
     * Shipment Counter.
     *
     * @var string
     */
    protected $time;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->carrierApi = new CarrierAPI();
        $this->apiShipment = new APIShipment();
        $this->apiController = new APIController();
        $this->users = [
            '8UNPY4' => '4b04b9488cefd96259d49c84cf02c2266c113db6',
            'DGDEV' => '46f03fd0cb61d7f072983683bdef4c630b8b9b41',
            'GDCDEV' => '16f01ed0cb61d6e472983683bdcf4c71bb8b9d41',
            'FAQSWB' => '46f01fd0cb61d6e072983683bdef4c710b8b9b41',
            'FPZ8EX' => 'zhuiugbh0yehqhmmfhlbbqxawefnakwr1zmysr3m',
            'FTPIOD' => '0eysm9wasrtsvpoza5inxbgyjezccasjpscbdzve',
        ];

        $this->time = time();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkDate = $this->argument('checkDate');
        if (is_null($this->checkDate)) {
            $this->checkDate = date('Y-m-d');
        }
        $this->selectShipments();

        foreach ($this->shipments as $shipment) {
            $this->cnt++;
            $msg = $this->callApi($shipment);
            $data = json_decode($msg, true);
            if (isset($data['errors']) && $data['errors'] != []) {
                foreach ($data['errors'] as $error) {
                    $this->errorMessages[] = 'Log Id : '.$shipment->id.' - '.$error['message']."\n";
                }
            }

            $this->displayResult($msg);
            sleep(1);
        }

        $this->displaySummary($this->cnt);
    }

    public function displayResult($msg)
    {
        if ($msg == 'legacy') {
            $this->legacy++;
        } else {
            $jsonData = json_decode($msg, true);

            if (isset($jsonData['data']['errors'])) {
                if ($jsonData['data']['errors'] == []) {
                    $this->correct++;
                    echo '.';
                } else {
                    $this->errors++;
                    echo 'x';
                }
            } else {
                $this->errors++;
                echo 'e';
            }
        }

        if ($this->cnt % 50 === 0) {

            // Pad things out to avoid being caught by throtling
            $timeTaken = time() - $this->time;
            $delay = 60 - $timeTaken;

            if ($delay > 0) {
                sleep($delay);
            }

            $this->time = time();

            echo ' '.$this->cnt."\n";
        }
    }

    public function displaySummary()
    {
        echo "\n".$this->cnt.' Shipments Selected, '
        .$this->legacy.' Legacy, '
        .$this->correct.' Passed validation, '
        ."$this->errors Failed validation\n\n";

        if ($this->errors > 0) {
            foreach ($this->errorMessages as $error) {
                echo $error;
            }
        }
    }

    public function selectShipments()
    {
        echo "**************************************************\n";
        echo "*     Selecting shipments to pass to the API     *\n";
        echo "*                                                *\n";
        echo "*     Using API transactions from $this->checkDate     *\n";
        echo "*                                                *\n";
        echo "*  Shipments will be validated but not created.  *\n";
        echo "*    Returns either a dummy shipment or error    *\n";
        echo "*                                                *\n";
        echo "* Note: No shipment details will actually change *\n";
        echo "*              ... Please Wait ...               *\n";
        echo "**************************************************\n";
        $this->shipments = TransactionLog::where('type', 'API')->where('created_at', '>', date('Y-m-d h:i:s', strtotime($this->checkDate.' 00:00:00')))->get();

        $cnt = count($this->shipments);

        echo "\n             ".$cnt." Shipments selected\n\n";
        echo "           Commencing Shipment Pricing\n\n";
        echo "**************************************************\n";
    }

    public function callApi($shipment)
    {
        $method = 'POST';
        $path = '/api/v2/shipments';

        // Retrieve JSON data and Decode it
        $shipmentArray = json_decode($shipment->msg, true);

        $apiToken = $this->getUserToken($shipmentArray['company_code']);

        if (isset($apiToken)) {

            // Mark shipment as APITEST and send it to the API
            $shipmentArray['options'] = ['APITEST'];
            $jsonData = json_encode($shipmentArray);

            $data = ['data' => $jsonData];

            return $this->sendMsg($data, $path, 'POST', $apiToken);
        } else {
            return 'legacy';
        }
    }

    public function getUserToken($companyCode)
    {
        $userIds = Company::where('company_code', $companyCode)->first()->users->pluck('id');
        $user = User::whereIn('id', $userIds)->orderBy('last_login', 'DESC')->first();

        if ($user) {
            return $user->api_token;
        } else {
            return;
        }
    }

    public function sendMsg($data, $path, $method, $api_token)
    {

        /*
         * ***************************
         * Send the XML Request
         * ***************************
         */

        $url = config('app.url');
        $api_token = '46f01fd0cb61d6e072983683bdef4c710b8b9b41';    // G Mc Broom

        $ch = curl_init(); // initialize curl handle

        $headers = [
            'Accept: application/vnd.api+json',
            "Authorization: Bearer $api_token",
        ];

        curl_setopt($ch, CURLOPT_URL, $url.$path); // set url to post to
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // return into a variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // add POST data -- this can be an array if you want to post like a regular form
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $result = curl_exec($ch);  // send!
        // Check for error  (Note : deliberate assignment in "if" statement)
        if ($error = curl_error($ch)) {
            echo 'ERROR: ', $error;
        }

        curl_close($ch); // close

        return $result;
    }
}
