<?php

namespace App;

use App\Rate;
use App\RateDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class RateDetail extends Model
{

    protected $fillable = [
        'rate_id',
        'residential',
        'piece_limit',
        'package_type',
        'zone',
        'break_point',
        'weight_rate',
        'weight_increment',
        'package_rate',
        'consignment_rate',
        'weight_units',
        'from_date',
        'to_date',
        'created_at',
        'updated_at'
    ];
    protected $dates = ['from_date', 'to_date'];
    public $timestamps = true;
    public $debug;

    public function scopeHasPieces($query, $pieces)
    {
        return $query->where('piece_limit', '>=', $pieces);
    }

    public function scopeHasPackageType($query, $packageType)
    {
        return $query->where('package_type', '>=', $packageType);
    }

    public function scopeHasZone($query, $zone)
    {
        return $query->where('zone', '=', $zone);
    }

    public function scopeHasWeight($query, $breakPoint)
    {
        return $query->where('break_point', '>=', $breakPoint);
    }

    /**
     * Given shipment details, retrieve one or more lines
     * from the rate tariff (whilst applying any appropriate
     * company specific discounts) depending on whether asked
     * for a rate or a table
     * 
     * @param type $companyId
     * @param type $rateId
     * @param type $residential
     * @param type $packagingType
     * @param type $pieces
     * @param type $weight
     * @param type $zone
     * @param type $shipDate
     * @return type
     */
    public function getRateDetails($companyId, $rateId, $serviceId, $residential = false, $packageType = '', $pieces = '', $weight = '', $zone = '', $shipDate = '', $limit = false, $precision = 2)
    {

        $SQL = "SELECT  rate_details.rate_id AS rate_id, 
                    rate_details.residential AS residential, 
                    rate_details.piece_limit AS piece_limit, 
                    rate_details.package_type AS package_type, 
                    rate_details.zone AS zone,
                    rate_details.break_point AS break_point,
                    ROUND(rate_details.weight_rate - COALESCE((rate_details.weight_rate * weight_discount)/100,0), :weight_precision) AS weight_rate,
                    ROUND(rate_details.package_rate - COALESCE((rate_details.package_rate * package_discount)/100,0), :package_precision) AS package_rate,
                    ROUND(rate_details.consignment_rate - COALESCE((rate_details.consignment_rate * consignment_discount)/100,0), :consignment_precision) AS consignment_rate,
                    rate_details.weight_increment AS weight_increment, 
                    rate_details.weight_units AS weight_units,
                    rate_details.from_date AS from_date, 
                    rate_details.to_date AS to_date
                FROM rate_details 
                LEFT JOIN rate_discounts 
                    ON  rate_discounts.rate_id = rate_details.rate_id
                    AND rate_discounts.service_id = :serviceId
                    AND rate_discounts.company_id = :companyId
                    AND rate_discounts.residential = rate_details.residential
                    AND rate_discounts.piece_limit = rate_details.piece_limit
                    AND rate_discounts.package_type = rate_details.package_type 
                    AND rate_discounts.zone = rate_details.zone 
                    AND rate_discounts.break_point = rate_details.break_point 
                    AND rate_discounts.from_date <= :fromDate1 
                    AND rate_discounts.to_date >= :toDate1
                WHERE   rate_details.rate_id = :rateId ";

        $PARAMS = [
            'weight_precision' => $precision,
            'package_precision' => $precision,
            'consignment_precision' => $precision,
            'serviceId' => $serviceId,
            'companyId' => $companyId,
            'fromDate1' => date('Y-m-d', strtotime($shipDate)),
            'toDate1' => date('Y-m-d', strtotime($shipDate)),
            'rateId' => $rateId
        ];

        if ($residential > '') {
            $SQL .= "AND rate_details.residential = :residential ";
            $PARAMS['residential'] = $residential;
        }
        if ($pieces > '') {
            $SQL .= "AND rate_details.piece_limit >= :pieces ";
            $PARAMS['pieces'] = $pieces;
        }
        if ($packageType > '') {
            $SQL .= "AND rate_details.package_type = :packageType ";
            $PARAMS['packageType'] = $packageType;
        }
        if ($zone > '') {
            $SQL .= "AND rate_details.zone = :zone ";
            $PARAMS['zone'] = $zone;
        }
        if ($weight > '') {
            $SQL .= "AND rate_details.break_point >= :weight ";
            $PARAMS['weight'] = $weight;
        }

        $PARAMS['fromDate2'] = date('Y-m-d', strtotime($shipDate));
        $PARAMS['toDate2'] = date('Y-m-d', strtotime($shipDate));
        $SQL .= "AND rate_details.from_date <= :fromDate2 "
                . "AND rate_details.to_date >= :toDate2 "
                . "ORDER BY rate_details.residential,rate_details.piece_limit,rate_details.package_type,rate_details.break_point,rate_details.zone";

        if ($limit) {
            $SQL .= " LIMIT 1";
        }

            if ($this->debug) {

            $message = rawToSql($SQL, $PARAMS) . ';';
            mail("debug@antrim.ifsgroup.com", "Pricing Analysis", $message);
        }

        // Get residential Rate Details for this Packaging Type/zone/pieces/weight
        return DB::select(DB::raw($SQL), $PARAMS);
    }

    public function getRate($companyId, $rateId, $serviceId, $recipientType, $packagingType, $pieces, $weight, $zone, $shipDate)
    {

        /*
         * ***********************************
         *  Check for Customer specific Tariff
         * ***********************************
         */
        // If a Residential address then look for a rate for a residential address
        if (strtolower($recipientType) == 'r') {

            $rate = $this->getRateDetails($companyId, $rateId, $serviceId, true, $packagingType, $pieces, $weight, $zone, $shipDate, true);
            if ($rate) {
                return $rate[0];
            }
        }

        // If Commercial address or no rate found then look for a rate for a commercial address
        if (empty($rate)) {

            $rate = $this->getRateDetails($companyId, $rateId, $serviceId, false, $packagingType, $pieces, $weight, $zone, $shipDate, true);
            if ($rate) {
                return $rate[0];
            }
        }
        /*
         * ***********************************
         *  Check for standard Tariff
         * ***********************************
         */
        // If a Residential address then look for a rate for a residential address
        if (strtolower($recipientType) == 'r') {

            $rate = $this->getRateDetails('0', $rateId, $serviceId, true, $packagingType, $pieces, $weight, $zone, $shipDate, true);
            if ($rate) {
                return $rate[0];
            }
        }

        // If Commercial address or no rate found then look for a rate for a commercial address
        $rate = $this->getRateDetails('0', $rateId, $serviceId, false, $packagingType, $pieces, $weight, $zone, $shipDate, true);

        // If rate found then return
        if ($rate) {
            return $rate[0];
        }
    }

    /**
     * Retrieves base rate table and applies any discounts
     * 
     * @param type $companyId
     * @param type $rate
     * @param type $shipDate
     * @return type Collection
     */
    public function getRateTable($companyId, $rateId, $serviceId, $shipDate = '', $precision = 4)
    {

        if ($shipDate == '') {

            $shipDate = date('Y-m-d');
        }

        return DB::select(DB::raw("
                SELECT  rate_details.rate_id AS rate_id, 
                    rate_details.residential AS residential, 
                    rate_details.piece_limit AS piece_limit, 
                    rate_details.package_type AS package_type, 
                    rate_details.zone AS zone,
                    rate_details.break_point AS break_point,
                    ROUND(rate_details.weight_rate, $precision) - ROUND(COALESCE((rate_details.weight_rate * weight_discount)/100,0), $precision) AS weight_rate,
                    ROUND(rate_details.package_rate, $precision) - ROUND(COALESCE((rate_details.package_rate * package_discount)/100,0), $precision) AS package_rate,
                    ROUND(rate_details.consignment_rate, $precision) - ROUND(COALESCE((rate_details.consignment_rate * consignment_discount)/100,0), $precision) AS consignment_rate,
                    rate_details.weight_increment AS weight_increment, 
                    rate_details.weight_units AS weight_units,
                    rate_details.from_date AS from_date, 
                    rate_details.to_date AS to_date
                FROM rate_details 
                LEFT JOIN rate_discounts 
                    ON  rate_discounts.rate_id = rate_details.rate_id
                    AND rate_discounts.company_id = :companyId
                    AND rate_discounts.service_id = :serviceId
                    AND rate_discounts.residential = rate_details.residential
                    AND rate_discounts.piece_limit = rate_details.piece_limit
                    AND rate_discounts.package_type = rate_details.package_type 
                    AND rate_discounts.zone = rate_details.zone 
                    AND rate_discounts.break_point = rate_details.break_point 
                    AND rate_discounts.from_date <= :fromDate1 
                    AND rate_discounts.to_date >= :toDate1
                WHERE rate_details.rate_id = :rateId AND rate_details.from_date <= :fromDate2 AND rate_details.to_date >= :toDate2
                ORDER BY rate_details.residential,rate_details.piece_limit,rate_details.package_type,rate_details.break_point,rate_details.zone
                "), ['companyId' => $companyId, 'serviceId' => $serviceId, 'fromDate1' => $shipDate, 'toDate1' => $shipDate, 'rateId' => $rateId, 'fromDate2' => $shipDate, 'toDate2' => $shipDate]
        );
    }

    public function getZones($rate, $shipDate)
    {

        // Get Zones used by this rate. Use orderByRaw to get a natural sort of zones
        return $this->distinct()->where('rate_id', $rate->id)
                        ->where('from_date', '<=', $shipDate)
                        ->where('to_date', '>=', $shipDate)
                        ->orderByRaw("LENGTH(zone)")
                        ->orderBy('zone')
                        ->groupBy('zone')
                        ->get()
                        ->pluck('zone');
    }

    public function buildQuery($query, $companyId, $rateId, $serviceId = '', $effectiveDate = '', $action = 'get')
    {

        // If No effective date set then use today
        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');
        }

        // Build query
        $sql = $query->when($companyId, function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })
                ->when($rateId, function ($query) use ($rateId) {
                    return $query->where('rate_id', $rateId);
                })
                ->when($serviceId, function ($query) use ($serviceId) {
                    return $query->where('service_id', $serviceId);
                })
                ->when($effectiveDate, function ($query) use ($effectiveDate) {
                    return $query->where('from_date', '<=', $effectiveDate);
                })
                ->when($effectiveDate, function ($query) use ($effectiveDate) {
            return $query->where('to_date', '>=', $effectiveDate);
        });

        return $sql->$action();
    }

    public function setRateDiscounts($companyId, $rateId, $serviceId = '', $discount = '0', $effectiveDate = '')
    {

        // Get Copy of Rate table so we can make a discount for each record
        $rateDetail = $this->buildQuery(new RateDetail(), '', $rateId, $serviceId, $effectiveDate, 'get');
        $discounts = [];

        // Now to create matching discounts
        foreach ($rateDetail as $rate) {

            $discounts[] = [
                'company_id' => $companyId,
                'rate_id' => $rateId,
                'service_id' => $serviceId,
                'residential' => $rate->residential,
                'piece_limit' => $rate->piece_limit,
                'package_type' => $rate->package_type,
                'zone' => $rate->zone,
                'break_point' => $rate->break_point,
                'weight_discount' => $discount,
                'package_discount' => $discount,
                'consignment_discount' => $discount,
                'from_date' => $rate->from_date,
                'to_date' => $rate->to_date,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        if ($discounts != []) {
            RateDiscount::create([$discounts]);
        }
    }

    function deleteRateDiscounts($companyId, $rateId = '', $serviceId = '', $effectiveDate = '')
    {
        $this->buildQuery(new RateDiscount(), $companyId, $rateId, $serviceId, $effectiveDate, 'delete');
    }

    public function clearExistingRate($companyId, $rateId = '', $serviceId = '', $effectiveDate = '')
    {
        // Clear any Customer Specific Rates
        $this->buildQuery(new RateDetail(), $companyId, $rateId, $serviceId, $effectiveDate, 'delete');
    }

    /**
     * Checks that the new rate is in the same format as the previous rate.
     * If so it then calculates what discoults need to be applied to the
     * standard rate to create the rate we have uploaded.
     * 
     * @param type $companyId
     * @param type $serviceId
     * @param type $rateId
     * @param type $currentRate
     * @param type $uploadedRate
     * @param type $effectiveDate
     * @return string
     */
    public function doRateUpload($companyId, $serviceId, $rateId, $currentRate, $uploadedRate, $effectiveDate = '')
    {

        // check old and new rates have same structure
        $currentKeys = $this->buildCurrentKeys($currentRate);
        $uploadedKeys = $this->buildUploadedKeys($uploadedRate);
        $diff = array_diff(array_keys($currentKeys), array_keys($uploadedKeys));
        if ($diff == []) {

            // Tables Match so build discounts
            $discounts = $this->buildDiscounts($currentKeys, $uploadedKeys, $companyId, $serviceId, $effectiveDate);
            if ($discounts != []) {

                // RateDiscount::insert($discounts);
                foreach (array_chunk($discounts, 1000) as $t) {

                    DB::table('rate_discounts')->insert($t);
                }
            }
            
        } else {
            return "Tables do not match";
        }
    }

    /**
     * Checks to see if any discounts are in place for the specified date.
     * If so, then depending on their "from_date" it will either delete them
     * or close them on the previous day.
     * 
     * @param type $companyId
     * @param type $rateId
     * @param type $serviceId
     * @param type $effectiveDate
     */
    public function closeRateDiscounts($companyId, $rateId = '', $serviceId = '', $effectiveDate = '')
    {

        // Get any domestic rates already defined
        $discounts = $this->buildQuery(new RateDiscount(), $companyId, $rateId, $serviceId, $effectiveDate, 'get');

        if ($discounts) {

            foreach ($discounts as $discount) {

                // If Rate only just defined or in the future - remove it so we can replace it.
                if ($discount->from_date >= date('Y-m-d')) {
                    $discount->delete();
                }

                // If pre-existing rate - close it.
                if ($discount->from_date < date('Y-m-d') && $discount->to_date >= date('Y-m-d')) {
                    $discount->to_date = date('Y-m-d', strtotime($effectiveDate . ' -1 day'));
                    $discount->update();
                }
            }
        }
    }

    /**
     * Check Keys of both arrays are identical
     * And if so return an array of discounts
     * 
     * @param type $currentRate
     * @param type $uploadedRate
     * @return type
     */
    public function buildDiscounts($currentKeys, $uploadedKeys, $companyId, $serviceId, $effectiveDate = '')
    {

        $discounts = [];
        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');                                     // If not specified then effective today
        }
        foreach ($currentKeys as $key => $current) {

            // Build discount details for this record
            $discount['company_id'] = $companyId;
            $discount['rate_id'] = $current['rate_id'];
            $discount['service_id'] = $serviceId;
            $discount['residential'] = $current['residential'];
            $discount['piece_limit'] = $current['piece_limit'];
            $discount['package_type'] = $current['package_type'];
            $discount['zone'] = $current['zone'];
            $discount['break_point'] = $current['break_point'];
            $discount['weight_discount'] = calcDiscPercentage($current['weight_rate'], $uploadedKeys[$key]['weight_rate']);
            $discount['package_discount'] = calcDiscPercentage($current['package_rate'], $uploadedKeys[$key]['package_rate']);
            $discount['consignment_discount'] = calcDiscPercentage($current['consignment_rate'], $uploadedKeys[$key]['consignment_rate']);
            $discount['from_date'] = $effectiveDate;
            $discount['to_date'] = '2099-12-31';

            // Only store discount if one of the values is non zero
            if ($discount['weight_discount'] <> 0 || $discount['package_discount'] <> 0 || $discount['consignment_discount'] <> 0) {
                $discounts[] = $discount;
            }
        }

        return $discounts;
    }

    /**
     * Given rate details, converts rate into an array
     * with a composite key of the key fields
     *  
     * @param type $rateTable
     * @param type $companyId
     * @return type
     */
    public function buildCurrentKeys($rateTable)
    {

        $keys = [];
        foreach ($rateTable as $row) {

            $key = '';
            $keyFields = ['residential', 'piece_limit', 'package_type', 'break_point'];
            foreach ($keyFields as $keyField) {
                if (isset($row->$keyField)) {

                    switch ($keyField) {
                        case 'break_point':
                            $key .= number_format(floatval($row->$keyField), 2) . "*";
                            break;

                        default:
                            $key .= $row->$keyField . "*";
                            break;
                    }
                }
            }


            if ($key > '') {
                $key .= $row->zone;
                $keys[$key]['rate_id'] = $row->rate_id;
                $keys[$key]['residential'] = $row->residential;
                $keys[$key]['piece_limit'] = $row->piece_limit;
                $keys[$key]['package_type'] = $row->package_type;
                $keys[$key]['zone'] = $row->zone;
                $keys[$key]['break_point'] = $row->break_point;
                $keys[$key]['weight_rate'] = $row->weight_rate;
                $keys[$key]['package_rate'] = $row->package_rate;
                $keys[$key]['consignment_rate'] = $row->consignment_rate;
                $keys[$key]['from_date'] = $row->from_date;
                $keys[$key]['to_date'] = $row->to_date;
            }
        }

        return $keys;
    }

    /**
     * Given rate details, converts rate into an array
     * with a composite key of the key fields
     * 
     * @param type $rateTable
     * @return type
     */
    public function buildUploadedKeys($rateTable)
    {

        $keys = [];
        $status = ['N' => 0, 'Y' => 1];
        foreach ($rateTable as $row) {

            $key = '';
            $keyFields = ['residential', 'piece_limit', 'package_type', 'break_point'];
            foreach ($keyFields as $keyField) {

                if (isset($row[$keyField])) {
                    switch ($keyField) {

                        case "residential":
                            $key .= $status[$row[$keyField]] . "*";
                            break;

                        case "break_point":
                            $breakPoint = '';
                            if (in_array(substr($row[$keyField], -3), ['/ea', '/kg'])) {
                                $breakPoint .= substr($row[$keyField], 0, strlen($row[$keyField]) - 3) . "*";
                            } else {
                                $breakPoint .= $row[$keyField] . "*";
                            }
                            $key .= number_format(floatval($breakPoint), 2) . "*";
                            break;

                        default:
                            $key .= $row[$keyField] . "*";
                            break;
                    }
                }
            }

            if ($key > '') {

                $fields = array_keys($row);

                foreach ($fields as $field) {

                    if (substr($field, 0, 5) == 'zone_') {

                        $zoneString = strtoupper(substr($field, 5));
                        $keyString = $key . $zoneString;
                        switch (substr($row['break_point'], -3)) {
                            case '/kg':
                                $keys[$keyString]['zone'] = $zoneString;
                                $keys[$keyString]['weight_rate'] = $row[$field];
                                $keys[$keyString]['package_rate'] = 0;
                                $keys[$keyString]['consignment_rate'] = 0;
                                break;

                            case '/ea':
                                $keys[$keyString]['zone'] = $zoneString;
                                $keys[$keyString]['weight_rate'] = 0;
                                $keys[$keyString]['package_rate'] = $row[$field];
                                $keys[$keyString]['consignment_rate'] = 0;
                                break;

                            default:
                                $keys[$keyString]['zone'] = $zoneString;
                                $keys[$keyString]['weight_rate'] = 0;
                                $keys[$keyString]['package_rate'] = 0;
                                $keys[$keyString]['consignment_rate'] = $row[$field];
                                break;
                        }
                    }
                }
            }
        }

        return $keys;
    }

}
