<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class Fuk_RateH extends Model
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
    protected $table = 'FUK_RateH';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /*
     * Variable to switch on debugging messages
     */
    public $debug = false;

    public function rateLines()
    {
        return $this->hasMany(Fuk_Rates::class, 'table_no', 'table_id');
    }

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

        if ($this->debug) {
            display("select * from FUK_RateH where Company = $company and app = \"$app\" and service = \"$service\" and valid_from <= \"$ship_date\" and valid_to >= \"$ship_date\";");
        }

        $rate = self::where('company', $company)
                        ->where('app', $app)
                        ->where('service', $service)
                        // ->where('table_id', $table)
                        ->where('valid_from', '<=', $ship_date)
                        ->where('valid_to', '>=', $ship_date)->first();

        if ($rate) {
            if ($this->debug) {
                display('Company Specific Rate');
            }

            return $rate->table_id;
        } else {

            // Try again with company = 4 (IFS default rates)
            $rate = self::where('company', '4')
                            ->where('app', $app)
                            ->where('service', $service)
                            //->where('table_id', $table)
                            ->where('valid_from', '<=', $ship_date)
                            ->where('valid_to', '>=', $ship_date)->first();
            if ($rate) {
                if ($this->debug) {
                    display('Company Default Rate');
                }

                return $rate->table_id;
            }
            if ($this->debug) {
                display('No Rate Found');
            }
        }
    }

    public function getCostTable($company = '', $app = '', $service = '', $table = '', $ship_date = '')
    {
        /*
         * ****************************************
         * Firstly check for a Customer Specific
         * Cost Table then fall back to generic
         * ****************************************
         */
        $result = $this->getRateTable($company, $app, $service.'_COST', $table, $ship_date);

        if ($this->debug) {
            display('getCostTable returning', $result);
        }

        return $result;
    }

    public function readLegacyRate($company, $service)
    {
        $rateId = $this->getRateTable($company->company, 'courierUK', $service->service, $company->UPSTable, date('Y-m-d'));

        if ($this->debug) {
            display("readLegacyRate - RateId : $rateId");
        }

        return Fuk_Rate::where('table_no', $rateId)
                        ->where('valid_from', '<=', date('Y-m-d'))
                        ->where('valid_to', '>=', date('Y-m-d'))
                        ->orderBy('piece_limit', 'p_type', 'zone', 'b_point')
                        ->get();
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
        $newRate = \App\Models\RateDetail::where('rate_id', $newRateId)
                ->where('from_date', '<=', date('Y-m-d'))
                ->where('to_date', '>=', date('Y-m-d'))
                ->orderBy('residential', 'piece_limit', 'package_type', 'zone', 'break_point')
                ->get();

        if ($this->ratesMatch($legacyRate, $newRate)) {
            $this->clearExistingDiscounts($company->company, $newRateId, $newService->id);
            foreach ($legacyRate as $row) {

                // Create Discount or return false
                if (! $this->addDiscount($row, $company->company, $newRateId, $newService)) {
                    $this->clearExistingDiscounts($company->company, $newRateId, $newService->id);

                    return false;
                }
            }

            return true;
        }

        // Rate Not Migrated
        return false;
    }

    public function clearExistingDiscounts($companyId, $newRateId, $service)
    {

        // Clear any existing discounts
        $rate = \App\Models\RateDiscount::where('rate_id', $newRateId)->delete();
    }

    public function addDiscount($row, $companyId, $newRateId, $newService)
    {

        // Read Current Standard Rate on new system
        $newRate = \App\Models\RateDetail::where('rate_id', $newRateId)
                ->where('residential', false)
                ->where('piece_limit', $row->piece_limit)
                ->where('package_type', $row->p_type)
                ->where('zone', $row->zone)
                ->where('break_point', $row->b_point)
                ->where('from_date', '<=', date('Y-m-d'))
                ->where('to_date', '>=', date('Y-m-d'))
                ->first();

        if ($newRate) {
            $weightDiscount = 0;
            $consignmentDiscount = 0;

            // calculate Customer discounts
            if ($newRate->weight_rate > 0) {
                $weightDiscount = calcDiscPercentage($newRate->weight_rate, $row->frt_rate);
            }
            if ($newRate->consignment_rate > 0) {
                $consignmentDiscount = calcDiscPercentage($newRate->weight_rate, $row->frt_rate);
            }

            // If a discounted rate then insert discount
            if ($weightDiscount != 0 || $consignmentDiscount != 0) {
                $rowDiscount = \App\Models\RateDiscount::create([
                            'company_id' => $companyId,
                            'rate_id' => $newRateId,
                            'service_id' => $newService->id,
                            'residential' => false,
                            'piece_limit' => $row->piece_limit,
                            'package_type' => $row->p_type,
                            'zone' => $row->zone,
                            'break_point' => $row->b_point,
                            'weight_discount' => $weightDiscount,
                            'package_discount' => 0,
                            'consignment_discount' => $consignmentDiscount,
                            'from_date' => $row->valid_from,
                            'to_date' => '2099-12-31',
                ]);
            }

            return true;
        } else {

            // No Rate Found
            echo "select * from rate_details where 'rate_id' = '".$newRateId."'"
            ." and 'residential' = '0'"
            ." and 'piece_limit' = '".$row->piece_limit."'"
            ." and 'package_type' = '".$row->p_type."'"
            ." and 'zone' = '".$row->zone."'"
            ." and 'break_point' = '".$row->b_point."'"
            ." and 'from_date' <= ".date('Y-m-d')."'"
            ." and 'to_date' >= ".date('Y-m-d')."'";
            dd($newRate);

            return false;
        }
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

        echo 'Legacy : '.count($oldKeys).' New '.count($newKeys).' ';
        display('Rates for following service do not match - '.count($diff).' differences');
        echo '<pre>';
        print_r($diff);
        echo '</pre>';

        return false;
    }

    public function buildLegacyKeys($rate, $service = '')
    {
        $packaging = ['K' => 'Package', 'CTN' => 'Package', 'package' => 'Package'];

        $keys = [];
        foreach ($rate as $row) {
            if (isset($packaging[$row['p_type']])) {
                $keys[] = strtoupper('0'.$row['piece_limit'].ucwords($packaging[$row['p_type']]).$row['zone'].$row['b_point']);
            } else {
                $keys[] = strtoupper('0'.$row['piece_limit'].strtoupper($row['p_type']).$row['zone'].$row['b_point']);
            }
        }

        return $keys;
    }

    public function buildNewKeys($rate, $service = '')
    {
        $keys = [];
        foreach ($rate as $row) {
            $keys[] = strtoupper($row->residential.$row->piece_limit.strtoupper($row->package_type).$row->zone.$row->break_point);
        }

        return $keys;
    }
}
