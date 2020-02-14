<?php

namespace App\Console\Commands;

use App\Company;
use App\Service;
use App\Shipment;
use DateTime;
use DateTimeZone;
use Illuminate\Console\Command;

class CheckPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-pricing {consignmentNumber?} {checkDate?} {debug?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Shipment Pricing';

    /**
     * Date of Shipments to price.
     *
     * @var string
     */
    protected $checkDate;

    /**
     * Shipments to be priced.
     *
     * @var string
     */
    protected $shipments;

    /**
     * Array of Companies to price.
     *
     * @var string
     */
    protected $companies;

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
    protected $unpriced = 0;

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
    protected $consignmentNumber;

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
    protected $debug = 0;

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
        if ($this->checkInput()) {
            $this->consignmentNumber = $this->argument('consignmentNumber');
            $this->debug = ($this->argument('debug') == 'debug');
            $this->selectShipments();

            foreach ($this->shipments as $shipment) {
                $this->cnt++;
                $msg = $this->callPricing($shipment);
                $this->displayResult($msg, $this->cnt);

                // If in debug mode then break at first error
                if ($this->debug && $msg > '') {
                    echo "\n\n*** Debug Mode ***\nBreaking at First Mismatch\n\n";
                    break;
                }
            }

            $this->displaySummary($this->cnt);
        } else {
            $this->displayHelp();
        }
    }

    public function check_date($str_dt, $str_dateformat, $str_timezone)
    {
        $date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));

        return $date && DateTime::getLastErrors()['warning_count'] == 0 && DateTime::getLastErrors()['error_count'] == 0;
    }

    protected function checkInput()
    {
        if ($this->argument('consignmentNumber') != null) {
            if ($this->argument('consignmentNumber') != 'all') {
                if (strlen($this->argument('consignmentNumber')) != 11) {
                    return false;
                }
            }
        }

        if ($this->argument('debug') != null && $this->argument('debug') != 'debug') {
            return false;
        }

        if ($this->argument('checkDate') && $this->check_date($this->argument('checkDate'), 'Y-m-d', 'UTC')) {
            $this->checkDate = date('Y-m-d', strtotime($this->argument('checkDate')));
        }

        return true;
    }

    protected function displayhelp()
    {
        $blank = '                                                                            ';
        $this->error($blank);
        $this->error('  Invalid Parameter(s)                                                      ');
        $this->error($blank);
        $this->error('  Please enter command in the following format                              ');
        $this->error($blank);
        $this->error('      php artisan ifs:check-pricing {consignment_number} {date} {debug}     ');
        $this->error($blank);
        $this->error('  e.g.                                                                      ');
        $this->error($blank);
        $this->error('      php artisan ifs:check-pricing 10007432939 2018-11-08 debug            ');
        $this->error('      php artisan ifs:check-pricing 10007432939                             ');
        $this->error('      php artisan ifs:check-pricing all 2018-11-08 debug                    ');
        $this->error('      php artisan ifs:check-pricing                                         ');
        $this->error($blank);
    }

    public function displaySummary()
    {
        echo "\n".$this->cnt.' Shipments Selected, '
        .$this->correct.' priced correctly, '
        .$this->unpriced.' unpriced, '
        ."$this->errors had issues\n";

        if ($this->errors > 0) {
            foreach ($this->errorMessages as $error) {
                echo $error;
            }
        }
    }

    public function selectShipments()
    {
        echo "**************************************************\n";
        echo "*         Selecting shipments to reprice         *\n";
        echo "*                                                *\n";
        echo "*    Note:                                       *\n";
        echo "*    No shipment details will actually change    *\n";
        echo "*              ... Please Wait ...               *\n";
        echo "**************************************************\n";
        if ($this->checkDate == null) {
            $this->checkDate = \Carbon\Carbon::today()->modify('last weekday')->format('Y-m-d');     // Last working day
        }
        $this->companies = Company::where('legacy', 0)->pluck('id')->toArray();                  // Get all Non Legacy Customers

        if (isset($this->consignmentNumber) && strlen($this->consignmentNumber) == 11) {
            $this->shipments = Shipment::whereIn('consignment_number', [$this->consignmentNumber])->get();
        } else {
            $this->shipments = Shipment::whereIn('company_id', $this->companies)
                    ->whereDate('collection_date', $this->checkDate)
                    ->whereNotNull('quoted')
                    ->get();
        }

        echo "\n             ".count($this->shipments)." Shipments selected\n\n";
        echo "           Commencing Shipment Pricing\n\n";
        echo "**************************************************\n";
    }

    public function displayResult($msg)
    {
        if ($msg == '') {
            $this->correct++;
            echo '.';
        } else {
            $this->errors++;
            echo 'x';
        }

        if ($this->cnt % 50 === 0) {
            echo ' '.$this->cnt."\n";
        }
    }

    public function callPricing($shipment)
    {
        $msg = '';
        $orig_cost = $shipment->shipping_cost;
        $orig_charge = $shipment->shipping_charge;
        $orig_quote = json_decode($shipment->quoted, true);

        $prices = $shipment->price(false);

        // If unable to price originally, dont check
        if ($orig_quote == 0 && $orig_charge == 0) {
            $this->unpriced++;

            return;
        }

        // Check Costs
        $msg = ($prices['shipping_cost'] != $orig_quote['shipping_cost']) ? 'Cost' : '';

        // Check Sales
        if ($prices['shipping_charge'] != $orig_quote['shipping_charge']) {
            $msg = ($msg == '') ? 'Sales' : 'Both';
        }

        if ($msg != '') {
            $this->buildErrorMessage($shipment, $msg, $orig_quote, $prices);
        }

        return $msg;
    }

    public function buildErrorMessage($shipment, $msg, $orig_quote, $prices)
    {
        $service = Service::find($shipment->service_id)->code;
        $companyName = Company::find($shipment->company_id)->company_name;
        $this->errorMessages[] = "\n".$shipment->consignment_number.' '.$msg
                .'  mismatch - Cost '.$orig_quote['shipping_cost'].' Reprice '.$prices['shipping_cost']
                .' Charge '.$orig_quote['shipping_charge'].' Reprice '.$prices['shipping_charge']."\n";

        $this->errorMessages[] = "\nCompany : $companyName (".$shipment->company_id.")\n";
        $this->errorMessages[] = "\nService : $service Pieces : $shipment->pieces Weight : $shipment->weight Vol : $shipment->volumetric_weight\n\n";

        if ($orig_quote['costs'] != $prices['costs']) {
            $this->errorMessages[] = $this->addPriceDetails('Costs', $orig_quote, $prices);
        }
        if ($orig_quote['sales'] != $prices['sales']) {
            $this->errorMessages[] = $this->addPriceDetails('Sales', $orig_quote, $prices);
        }
        $this->errorMessages[] = str_repeat('*', 105)."\n";
    }

    public function addPriceDetails($type, $quoted, $calc)
    {
        $msg = '';
        $orig = $quoted[strtolower($type)];
        $priced = $calc[strtolower($type)];
        $origCount = count($orig);
        $pricedCount = count($priced);
        $maxEntries = max($origCount, $pricedCount);

        $this->errorMessages[] = "  $type - Original Zone ".$quoted[strtolower($type).'_zone'].' Priced Zone '.$calc[strtolower($type).'_zone']."\n";
        for ($i = 0; $i < $maxEntries; $i++) {
            $msg = '';

            // Display Original Line if it exists
            if (isset($orig[$i]['code'])) {
                $msg .= sprintf('    [%-5s] [%-25s] [%7.2f]   -   ', $orig[$i]['code'], $orig[$i]['description'], $orig[$i]['value']);
            } else {
                $msg .= str_repeat(' ', 56);
            }

            // Display Priced Line if it exists
            if (isset($priced[$i]['code'])) {
                $msg .= sprintf('[%-5s] [%-25s] [%7.2f]', $priced[$i]['code'], $priced[$i]['description'], $priced[$i]['value']);
            }

            if ($msg != '') {
                $msg .= "\n";
                $this->errorMessages[] = $msg;
            }
        }
    }
}
