<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;

class IncreaseCostRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:increase-cost-rate {rateId?} {percentage?} {fromDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increase Cost Rate';

    protected $rate;
    protected $rateId;
    protected $increasePercentage;
    protected $multiplier;
    protected $fromDate;
    protected $toDate;
    protected $endDate;
    protected $error = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->fromDate = date('Y-m-d', strtotime('first day of january next year'));
        $this->toDate = '2099-12-31';                                      // date('Y-m-d', strtotime('last day of december next year'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->rateId = $this->argument('rateId');
        $this->increasePercentage = $this->argument('percentage');
        $this->setDates($this->argument('fromDate'));
        if ($this->checkInput()) {
            $this->multiplier = 1 + $this->increasePercentage / 100;
            $this->rate = \App\Models\Rate::find($this->rateId);
            if ($this->rate) {
                $this->checkAbleToIncrease();
                $this->info('');
                $this->info('Increasing rate '.$this->rateId.' by '.$this->increasePercentage.'%');
                $this->info('Please wait, this may take a while...');

                $this->info('    FromDate: '.$this->fromDate);
                $this->info('    ToDate:   '.$this->toDate);
                $this->info('    EndDate:  '.$this->endDate);

                if ($this->rate->model == 'domestic') {
                    $this->info('Increasing Domestic Rate');
                    $this->increaseDomesticRates();
                } else {
                    $this->info('Increasing International Rate');
                    $this->increaseIntlRates();
                }
                $this->info('Rates Increased Successfully');
                $this->info('');
            }
        } else {
            $this->displayHelp();
        }

        if ($this->error) {
            $this->closeError();
        }
    }

    protected function checkInput()
    {
        $this->checkRateId();
        $this->checkPercentage();

        // Format date
        if ($this->argument('fromDate') && $this->check_date($this->argument('fromDate'), 'Y-m-d', 'UTC')) {
            $this->checkDate = date('Y-m-d', strtotime($this->argument('fromDate')));
        }

        return true;
    }

    public function check_date($str_dt, $str_dateformat, $str_timezone)
    {
        $date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));

        return $date && DateTime::getLastErrors()['warning_count'] == 0 && DateTime::getLastErrors()['error_count'] == 0;
    }

    public function setDates($fromDate = '')
    {
        if ($this->validDate($fromDate)) {
            $this->fromDate = $fromDate;
        }

        $this->endDate = date('Y-m-d', strtotime($this->fromDate.' -1 day'));
    }

    public function validDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    /**
     * Check there are no conflicting rates already in place.
     */
    protected function checkAbleToIncrease()
    {
        $rates = \App\Models\Models\DomesticRate::where('rate_id', $this->rate->id)
                ->where('from_date', '>=', $this->fromDate)
                ->first();
        if ($rates) {
            $this->unableToApplyIncrease('domestic_rates');
        }

        $rates = \App\Models\RateDetail::where('rate_id', $this->rate->id)
                ->where('from_date', '>=', $this->fromDate)
                ->first();
        if ($rates) {
            $this->unableToApplyIncrease(' rate_details ');
        }
    }

    protected function invalidDate()
    {
        $blank = '                                                                                       ';
        $this->error($blank);
        $this->error('  Unable to perform Increase.                                                          ');
        $this->error($blank);
        $this->error('  Date is invalid. Required in format YYYY-mm-dd                                       ');
        $this->error($blank);
        $this->error('  Please correct and try again.                                                        ');
        $this->error($blank);
        exit();
    }

    protected function unableToApplyIncrease($table)
    {
        $blank = '                                                                                      ';
        $this->error($blank);
        $this->error('  Unable to perform Increase.                                                         ');
        $this->error($blank);
        $this->error('  Rate: '.$this->rate->id.' already contains rates for all or part of the period                       ');
        $this->error($blank);
        $this->error('                    '.$this->fromDate.' to '.$this->toDate.'                                          ');
        $this->error($blank);
        $this->error('  Please remove these rates and try again.                                            ');
        $this->error($blank);
        exit();
    }

    protected function checkPercentage()
    {
        if (is_numeric($this->increasePercentage)) {
            if ($this->increasePercentage > 0 && $this->increasePercentage < 5) {
                return;
            }
        }

        $this->error('  Invalid Percentage.                                                                 ');
        $this->error = true;
    }

    protected function checkRateId()
    {
        if ($this->rate) {
            return;
        }

        $this->error('  Invalid RateId.                                                                     ');
        $this->error = true;
    }

    protected function closeError()
    {
        $this->displayhelp();
        exit();
    }

    public function increaseDomesticRates()
    {
        $rates = \App\Models\Models\DomesticRate::where('rate_id', $this->rate->id)
                        ->where('to_date', '>', date('Y-m-d'))
                        ->orderBy('service')
                        ->orderBy('packaging_code')
                        ->orderBy('area')
                        ->get();

        // Cycle through rates selected and increase values
        foreach ($rates as $rate) {
            \App\Models\Models\DomesticRate::create([
                'rate_id' => $rate->rate_id,
                'service' => $rate->service,
                'packaging_code' => $rate->packaging_code,
                'first' => round($rate->first * $this->multiplier, 4),
                'others' => round($rate->others * $this->multiplier, 4),
                'notional_weight' => $rate->notional_weight,
                'notional' => round($rate->notional * $this->multiplier, 4),
                'area' => $rate->area,
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
            ]);

            $rate->update(['to_date' => $this->endDate]);
        }
    }

    public function increaseIntlRates()
    {
        $rates = \App\Models\RateDetail::where('rate_id', $this->rate->id)
                ->where('to_date', '>', date('Y-m-d'))
                ->orderBy('residential')
                ->orderBy('piece_limit')
                ->orderBy('zone')
                ->orderBy('package_type')
                ->orderBy('break_point')
                ->get();

        // Cycle through rates selected and increase values
        foreach ($rates as $rate) {
            \App\Models\RateDetail::create([
                'rate_id' => $rate->rate_id,
                'residential' => $rate->residential,
                'piece_limit' => $rate->piece_limit,
                'package_type' => $rate->package_type,
                'zone' => $rate->zone,
                'break_point' => $rate->break_point,
                'weight_rate' => round($rate->weight_rate * $this->multiplier, 4),
                'weight_increment' => $rate->weight_increment,
                'package_rate' => round($rate->package_rate * $this->multiplier, 4),
                'consignment_rate' => round($rate->consignment_rate * $this->multiplier, 4),
                'weight_units' => $rate->weight_units,
                'from_date' => $this->fromDate,
                'to_date' => $this->toDate,
            ]);
            $rate->update(['to_date' => $this->endDate]);
        }
    }

    public function createEmail()
    {

        // Set email subject
        $subject = 'Courier Rate Increase';

        // Build Message text
        $message = 'Please note that all customer rates have been increased by '.$this->increasePercentage.'%';
        $message .= ' effective '.$this->fromDate.'. These rates will remain active until '.$this->toDate." unless further changes are applied.\n\n";

        mail($this->contactEmail, $subject, $message);
    }

    protected function displayhelp()
    {
        $blank = '                                                                                      ';
        $this->error($blank);
        $this->error('  Please enter command in the following format                                        ');
        $this->error($blank);
        $this->error('      php artisan ifs:increase-cost-rate {rate_id} {percentage} {Date Effective From?}');
        $this->error($blank);
        $this->error('  e.g.                                                                                ');
        $this->error($blank);
        $this->error('      php artisan ifs:increase-cost-rate 10 3.5 2020-01-06                            ');
        $this->error('      php artisan ifs:increase-cost-rate 10 3.5     (defaults to 01 Jan next year)    ');
        $this->error($blank);
        $this->error('      rate_id must be an integer and valid rate_id.                                   ');
        $this->error('      percentage must be a positive number greater than zero and less than 5          ');
        $this->error('      Date Effective from must be a valid date in format 2020-01-06                   ');
        $this->error($blank);
    }
}
