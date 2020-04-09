<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FukRate extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FUKRates';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;
    public $debug = false;
    public $packagingCodes;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->packagingCodes = ['K' => 'Package', 'CTN' => 'Package'];
    }

    public function readLegacyRate($company, $service)
    {
        if ($company->UKRate == '') {
            $rateTable = 'undefined';
        } else {
            $rateTable = $company->UKRate;
        }

        if ($this->debug) {
            display("select * from FUKRates where rate = \"$rateTable\" and compID = \"".$company->company.'" and service = "'.strtoupper($service->service).'" and effective_from <= "'.date('Y-m-d').'" and effective_to >= "'.date('Y-m-d').'";');
        }

        // Look for company specific rate that is currently valid
        $rates = $this->where('rate', $rateTable)
                ->where('compID', $company->company)
                // ->where('service', strtoupper($service->service))
                ->where('effective_from', '<=', date('Y-m-d'))
                ->where('effective_to', '>=', date('Y-m-d'))
                ->orderBy('service', 'packaging', 'area')
                ->get();

        if ($rates->isEmpty()) {
            if ($this->debug) {
                display("select * from FUKRates where rate = \"$rateTable\" and service = \"".strtoupper($service->service).'" and effective_from <= "'.date('Y-m-d').'" and effective_to >= "'.date('Y-m-d').'";');
            }

            // Look for company specific rate that is currently valid
            $rates = $this->where('rate', $rateTable)
                    // ->where('service', strtoupper($service->service))
                    ->where('effective_from', '<=', date('Y-m-d'))
                    ->where('effective_to', '>=', date('Y-m-d'))
                    ->orderBy('service', 'packaging', 'area')
                    ->get();
        }

        return $rates;
    }

    /**
     * Migrate Rate from old system to the New System.
     *
     * @param type $companyId
     * @param type $service
     * @param type $oldRate
     */
    public function migrate($company, $legacyRate, $newRateId, $newService)
    {
        $newRate = \App\Models\DomesticRate::where('rate_id', $newRateId)
                ->where('from_date', '<=', date('Y-m-d'))
                ->where('to_date', '>=', date('Y-m-d'))
                ->orderBy('service', 'packaging_code', 'area')
                ->get();

        if ($this->ratesMatch($legacyRate, $newRate)) {
            $this->clearExistingDiscounts($company->company, $newRateId);
            foreach ($legacyRate as $row) {

                // Create Discount or return false
                if (! $this->addDiscount($row, $company->company, $newRateId, strtolower($row['service']))) {
                    $this->clearExistingDiscounts($company->company, $newRateId);

                    return false;
                }
            }

            return true;
        }

        // Rate Not Migrated
        return false;
    }

    public function clearExistingDiscounts($companyId, $newRateId)
    {

        // Clear any existing discounts
        $rate = \App\Models\DomesticRateDiscount::where('company_id', $companyId)
                ->where('rate_id', $newRateId)
                ->delete();
    }

    public function addDiscount($row, $companyId, $newRateId, $newService)
    {
        if (isset($this->packagingCodes[$row->packaging])) {
            $packagingCode = $this->packagingCodes[$row->packaging];
        } else {
            $packagingCode = $row->packaging;
        }

        // Read Standard Rate on new system
        $newRate = \App\Models\DomesticRate::where('rate_id', $newRateId)
                ->where('service', $newService)
                ->where('packaging_code', $packagingCode)
                ->where('area', $row->area)
                ->first();

        if ($newRate) {
            $firstDiscount = 0;
            $othersDiscount = 0;
            $notionalDiscount = 0;

            // Calculate Customers discount
            if ($newRate->first > 0) {
                $firstDiscount = calcDiscPercentage($newRate->first, $row->first);
            }
            if ($newRate->others > 0) {
                $othersDiscount = calcDiscPercentage($newRate->others, $row->others);
            }
            if ($newRate->notional > 0) {
                $notionalDiscount = calcDiscPercentage($newRate->notional, $row->notional);
            }

            if ($firstDiscount != 0 || $othersDiscount != 0 || $notionalDiscount != 0) {

                // If Service already defined then reset it, else create it.
                $rowDiscount = \App\Models\DomesticRateDiscount::create([
                            'company_id' => $companyId,
                            'rate_id' => $newRateId,
                            'service' => $newService,
                            'packaging_code' => $packagingCode,
                            'area' => $row->area,
                            'first_discount' => $firstDiscount,
                            'others_discount' => $othersDiscount,
                            'notional_discount' => $notionalDiscount,
                            'from_date' => $row->effective_from,
                            'to_date' => '2099-12-31',
                ]);
            }

            return true;
        } else {

            // No Rate Found
            echo "select * from rate_details where 'rate_id' = '".$newRateId."'"
            ." and 'service' = '0'"
            ." and 'packaging_code' = '".$row->p_type."'"
            ." and 'area' = '".$row->zone."'";
            dd($newRate);

            return false;
        }
    }

    public function display($message, $data)
    {
        echo "*** $message***<pre>";
        print_r($data);
        echo '</pre>';
    }

    public function ratesMatch($oldRate, $newRate)
    {
        $oldKeys = [];
        $newKeys = [];

        $oldKeys = $this->buildLegacyKeys($oldRate);
        $newKeys = $this->buildNewKeys($newRate);

        $diff = array_diff($oldKeys, $newKeys);

        if (empty($diff)) {
            return true;
        }

        display('Rates for following service do not match - Legacy : '.count($oldKeys).' New '.count($newKeys));

        return false;
    }

    public function buildLegacyKeys($rate)
    {
        $keys = [];
        foreach ($rate as $row) {
            if (isset($this->packagingCodes[$row['packaging']])) {
                $keys[] = strtoupper($row['service'].$this->packagingCodes[$row['packaging']].$row['area']);
            } else {
                $keys[] = strtoupper($row['service'].$row['packaging'].$row['area']);
            }
        }

        return $keys;
    }

    public function buildNewKeys($rate)
    {
        $keys = [];
        foreach ($rate as $row) {
            $keys[] = strtoupper($row->service.$row->packaging_code.$row->area);
        }

        return $keys;
    }
}
