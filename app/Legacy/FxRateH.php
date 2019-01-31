<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FxRateH extends Model {

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
    protected $table = 'FX_RateH';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /*
     * Debug Flag
     */
    public $debug = false;

    public function getRateTable($company = '', $app = '', $service = '', $table = '', $ship_date = '')
    {
        /*
         * ***************************************
         * Tables may be retrieved by :-
         * 
         *      Company/ Service
         *      Table name/ Service
         * ***************************************
         */

        $rate = $this->findRate($company, $app, $service, $table, $ship_date);

        if ($rate) {

            if ($this->debug)
                echo "Company Specific Rate found : " . $rate->id . "<br>";

            return $rate;
        }

        // Try again with company = DEF
        $rate = $this->findRate('DEF', $app, $service, $table, $ship_date);

        if ($rate) {
            if ($this->debug)
                echo "Company Default Rate found : " . $rate->id . "<br>";

            return $rate;
        }

        if ($this->debug)
            echo "No Rate Found<br>";


        return NULL;
    }

    function findRate($company, $app, $service, $table, $ship_date)
    {

        if ($this->debug)
            display("select * from FX_RateH where company = \"$company\" and app = \"$app\" and Service = \"$service\" and table_id = \"$table\" and valid_from <= \"$ship_date\" and valid_to >= \"$ship_date\"");

        if (strtoupper($table == 'COST')) {

            return FxRateH::where('company', $company)
                            ->where('app', $app)
                            ->where('service', $service)
                            ->where('table_id', $table)
                            ->where('valid_from', '<=', $ship_date)
                            ->where('valid_to', '>=', $ship_date)->first();
        } else {

            if ($table > '') {

                return FxRateH::where('company', $company)
                                ->where('app', $app)
                                ->where('service', $service)
                                ->where('table_id', $table)
                                ->where('table_id', '!=', 'Cost')
                                ->where('valid_from', '<=', $ship_date)
                                ->where('valid_to', '>=', $ship_date)->first();
            } else {

                return FxRateH::where('company', $company)
                                ->where('app', $app)
                                ->where('service', $service)
                                ->where('table_id', '!=', 'Cost')
                                ->where('valid_from', '<=', $ship_date)
                                ->where('valid_to', '>=', $ship_date)->first();
            }
        }
    }

    function getCostTable($company = '', $app = '', $service = '', $table = '', $ship_date = '')
    {
        /*
         * ****************************************
         * Firstly check for a Customer Specific
         * Cost Table then fall back to generic
         * ****************************************
         */
        $rate_id = '';
        $rate_id = $this->getRateTable($company, $app, $service, 'Cost', $ship_date);
        if ($rate_id == '') {

            // If no Customer specific costs then use "IFS" generic cost table
            $rate_id = $this->getRateTable('4', $app, $service, $table, $ship_date);
        }
        return $rate_id;
    }

    public function readLegacyRate($company, $service)
    {

        $rateId = -1;

        if ($service->gateway == 'UPS') {
            $tableName = $company->UPSTable;
        } else {
            $tableName = $company->FXTable;
        }

        switch (strtoupper($service->gateway)) {
            case 'FXRS':
                $gateway = 'FDX';
                break;

            default:
                $gateway = strtoupper($service->gateway);
                break;
        }

        if ($this->debug)
            display("Service : " . $service->code . " Tablename : $tableName");

        // If Tablename not defined then there is no table
        if ($tableName > '') {

            $rateHeader = $this->getRateTable($company->company, 'courier' . $gateway, $service->service, $tableName, date('Y-m-d'));

            if ($this->debug)
                display($rateHeader, "Rate Header");

            if (isset($rateHeader->id) && $rateHeader->id != '') {
                $rateId = $rateHeader->id;
            }
        }

        return \App\Legacy\FxRate::where('table_no', $rateId)
                        ->where('zone', '!=', 'F')
                        ->where('b_point', '<', '99999.00')
                        ->orderBy('residential', 'piece_limit', 'p_type', 'zone', 'b_point')
                        ->get();
    }

    /**
     * Migrate Rate from old system to the New System
     *
     * @param type $companyId
     * @param type $service
     * @param type $oldRate
     */
    public function migrate($company, $legacyRate, $newRateId, $newService)
    {

        if ($legacyRate) {

            $newRate = \App\RateDetail::where('rate_id', $newRateId)
                    ->where('from_date', '<=', date('Y-m-d'))
                    ->where('to_date', '>=', date('Y-m-d'))
                    ->orderBy('residential', 'piece_limit', 'package_type', 'zone', 'break_point')
                    ->get();

            if ($this->ratesMatch($legacyRate, $newRate)) {

                $this->clearExistingDiscounts($company->company, $newRateId, $newService->id);
                foreach ($legacyRate as $row) {

                    // Create Discount or return false
                    if (!$this->addDiscount($row, $company->company, $newRateId, $newService)) {
                        $this->clearExistingDiscounts($company->company, $newRateId, $newService->id);
                        return false;
                    }
                }

                return true;
            }
        }

        // Rate Not Migrated
        return false;
    }

    public function clearExistingDiscounts($companyId, $newRateId, $service)
    {

        // Clear any existing discounts
        $rate = \App\RateDiscount::where('company_id', $companyId)->where('service_id', $service)->delete();
    }

    public function addDiscount($row, $companyId, $newRateId, $newService)
    {

        $flag = ['Y' => true, 'N' => false, 'y' => true, 'n' => false];

        // Read Standard Rate on new system
        $newRate = \App\RateDetail::where('rate_id', $newRateId)
                ->where('residential', $flag[$row->residential])
                ->where('piece_limit', $row->piece_limit)
                ->where('package_type', $row->p_type)
                ->where('zone', $row->zone)
                ->where('break_point', $row->b_point)
                ->where('from_date', '<=', date('Y-m-d'))
                ->where('to_date', '>=', date('Y-m-d'))
                ->first();

        if ($newRate) {

            $weightDiscount = 0;
            $packageDiscount = 0;
            $consignmentDiscount = 0;

            // Calculate Customer Discounts
            if ($newRate->weight_rate != 0) {
                $weightDiscount = calcDiscPercentage($newRate->weight_rate, $row->frt_rate);
            }
            if ($newRate->consignment_rate != 0) {
                $consignmentDiscount = calcDiscPercentage($newRate->consignment_rate, $row->std_rate);
            }

            // If a discounted rate then insert discount
            if ($newRate->weight_rate != 0 || $newRate->consignment_rate != 0) {

                $rowDiscount = \App\RateDiscount::create([
                            'company_id' => $companyId,
                            'rate_id' => $newRateId,
                            'service_id' => $newService->id,
                            'residential' => $flag[$row->residential],
                            'piece_limit' => $row->piece_limit,
                            'package_type' => $row->p_type,
                            'zone' => $row->zone,
                            'break_point' => $row->b_point,
                            'weight_discount' => $weightDiscount,
                            'package_discount' => $packageDiscount,
                            'consignment_discount' => $consignmentDiscount,
                            'from_date' => $newRate->from_date,
                            'to_date' => '2099-12-31'
                ]);
            }

            return true;
        } else {

            // No Rate Found
            echo "select * from rate_details where 'rate_id' = '" . $newRateId . "'"
            . " and 'residential' = '0'"
            . " and 'piece_limit' = '" . $row->piece_limit . "'"
            . " and 'package_type' = '" . $row->p_type . "'"
            . " and 'zone' = '" . $row->zone . "'"
            . " and 'break_point' = '" . $row->b_point . "'"
            . " and 'from_date' <= " . date('Y-m-d') . "'"
            . " and 'to_date' >= " . date('Y-m-d') . "'";
            dd($newRate);
            return false;
        }
    }

    public function display($message, $data)
    {

        echo "*** $message***<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function ratesMatch($oldRate, $newRate)
    {

        $oldKeys = [];
        $newKeys = [];
        $oldKeys = $this->buildLegacyKeys($oldRate);
        $newKeys = $this->buildNewKeys($newRate);

        $diff = array_diff($oldKeys, $newKeys);

        if (empty($diff))
            return true;

        echo "Legacy : " . count($oldKeys) . " New " . count($newKeys) . " ";
        display("Rates for following service do not match - " . count($diff) . " differences");
        echo "<pre>";
        print_r($oldKeys);
        print_r($newKeys);
        echo "</pre>";
        return false;
    }

    public function buildLegacyKeys($rate)
    {

        $flag = ['N' => 0, 'Y' => 1];

        foreach ($rate as $row) {

            try {
                $keys[] = strtoupper($flag[$row['residential']] . $row['piece_limit'] . $row['p_type'] . $row['zone'] . $row->b_point);
            } catch (Exception $exc) {

                echo $exc->getTraceAsString();
            }
        }

        return $keys;
    }

    public function buildNewKeys($rate)
    {

        $keys = [];
        foreach ($rate as $row) {
            $keys[] = strtoupper($row->residential . $row->piece_limit . $row->package_type . $row->zone . $row->break_point);
        }

        return $keys;
    }

}
