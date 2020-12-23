<?php

namespace App\Console\Commands;

use App\Models\DomesticRate;
use App\Models\RateDetail;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PerformRateIncrease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:perform-rate-increase {percentage?} {mode?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform End of Year rate increase for all customers';

    /**
     * Rate increase percentage.
     *
     * @var decimal
     */
    protected $increasePercentage = 0;

    /**
     * Mode of transport to be updated
     * (A)ll (D)omestic (I)nternational
     *
     * @var string
     */
    protected $mode = 'A';
    protected $modeDesc = [];

    /**
     * Date rate effective from.
     *
     * @var string
     */
    protected $fromDate = '';

    /**
     * Date Rate effective to.
     *
     * @var string
     */
    protected $toDate = '';

    /**
     * Contact email address to send confirmation email to.
     *
     * @var string
     */
    protected $contactEmail = 'gmcb@antrim.ifsgroup.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->modeDesc = ['A' => 'all', 'D' => 'domestic', 'I' => 'international'];

        if (empty($this->fromDate)) {
            $this->fromDate = date('Y-m-d', strtotime('first day of january next year'));
        }
        if (empty($this->toDate)) {
            $this->toDate = date('Y-m-d', strtotime('last day of december next year'));
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->mode = strtoupper($this->argument('mode'));
        $this->increasePercentage = $this->argument('percentage');
        $this->checkMode();
        $this->checkPercentage();
        $this->checkAbleToIncrease();
        $multiplier = 1 + $this->increasePercentage / 100;

        $this->info('');
        $this->info('Increasing rates by '.$this->increasePercentage.'%');
        $this->info('Please wait, this may take a while...');

        if (in_array($this->mode, ["A","D"])) {
            echo "Increase Domestic Rates\n";
            $this->increaseDomesticRates($multiplier);
        }

        if (in_array($this->mode, ["A","I"])) {
            echo "Increase International Rates\n";
            $this->increaseIntlRates($multiplier);
        }

        $this->info('Rates Increased Successfully');
        $this->info('');
        $this->createEmail();
    }

    /**
     * Check mode
     *
     */
    protected function checkMode()
    {
        if (in_array($this->mode, ['A','D','I'])) {
            return true;
        }

        $blank = '                                                          ';
        $this->error($blank);
        $this->error('  Invalid Mode.                                           ');
        $this->error($blank);
        exit();
    }

    /**
     * Check there are no conflicting rates already in place.
     */
    protected function checkAbleToIncrease()
    {
        if (in_array($this->mode, ["A","D"])) {
            $rates = DomesticRate::where('rate_id', '>=', '500')->where('to_date', '>', $this->fromDate)->first();
            if ($rates) {
                $this->unableToApplyIncrease('domestic_rates');
            }
        }

        if (in_array($this->mode, ["A","I"])) {
            $rates = RateDetail::where('rate_id', '>=', '500')
                ->where('to_date', '>', $this->fromDate)
                ->first();
            if ($rates) {
                $this->unableToApplyIncrease(' rate_details ');
            }
        }
    }

    protected function unableToApplyIncrease($table)
    {
        $blank = '                                                                 ';
        $this->error($blank);
        $this->error('  Unable to perform Increase.                                    ');
        $this->error($blank);
        $this->error("  Table : $table already contains rates for the period   ");
        $this->error($blank);
        $this->error('                    '.$this->fromDate.' to '.$this->toDate.'                     ');
        $this->error($blank);
        $this->error('  Please remove these rates and try again.                       ');
        $this->error($blank);
        exit();
    }

    protected function checkPercentage()
    {
        if (is_numeric($this->increasePercentage)) {
            if ($this->increasePercentage > 0 && $this->increasePercentage < 6) {
                return;
            }
        }

        $blank = '                                                                 ';
        $this->error($blank);
        $this->error('  Invalid Percentage.                                            ');
        $this->error($blank);
        $this->error('  Please enter command in the following format                   ');
        $this->error($blank);
        $this->error('      php artisan ifs:perform-rate-increase {percentage} {mode}  ');
        $this->error($blank);
        $this->error('  e.g.                                                           ');
        $this->error($blank);
        $this->error('      php artisan ifs:perform-rate-increase 3.5 A                ');
        $this->error($blank);
        $this->error('      Where mode is (A)ll, (D)omestic or (I)nternational         ');
        $this->error($blank);
        exit();
    }

    public function increaseDomesticRates($multiplier)
    {
        $rates = DomesticRate::where('rate_id', '>=', '500')
                ->where('to_date', '>', date('Y-m-d'))
                ->get();

        // Cycle through rates selected and increase values
        foreach ($rates as $rate) {
            DomesticRate::create([
                'rate_id' => $rate->rate_id,
                'service' => $rate->service,
                'packaging_code' => $rate->packaging_code,
                'first' => round($rate->first * $multiplier, 4),
                'others' => round($rate->others * $multiplier, 4),
                'notional_weight' => $rate->notional_weight,
                'notional' => round($rate->notional * $multiplier, 4),
                'area' => $rate->area,
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
            ]);
        }
    }

    public function increaseIntlRates($multiplier)
    {
        $rates = RateDetail::where('rate_id', '>=', '500')
                ->where('to_date', '>', date('Y-m-d'))
                ->get();

        // Cycle through rates selected and increase values
        foreach ($rates as $rate) {
            RateDetail::create([
                'rate_id' => $rate->rate_id,
                'residential' => $rate->residential,
                'piece_limit' => $rate->piece_limit,
                'package_type' => $rate->package_type,
                'zone' => $rate->zone,
                'break_point' => $rate->break_point,
                'weight_rate' => round($rate->weight_rate * $multiplier, 4),
                'weight_increment' => $rate->weight_increment,
                'package_rate' => round($rate->package_rate * $multiplier, 4),
                'consignment_rate' => round($rate->consignment_rate * $multiplier, 4),
                'weight_units' => $rate->weight_units,
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
            ]);
        }
    }

    public function createEmail()
    {

        // Set email subject
        $subject = 'Courier Rate Increase';

        // Build Message text
        $message = 'Please note that '.$this->modeDesc[$this->mode].' customer rates have been increased by '.$this->increasePercentage.'%';
        $message .= ' effective '.$this->fromDate.'. These rates will remain active until '.$this->toDate." unless further changes are applied.\n\n";

        mail($this->contactEmail, $subject, $message);
    }
}
