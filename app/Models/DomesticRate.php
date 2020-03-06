<?php

namespace App\Models\Models;

use App\Models\Models\DomesticRateDiscount;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DomesticRate extends Model
{
    protected $guarded = ['id'];
    public $timestamps = true;
    public $debug = false;

    /**
     * Given shipment details, retrieve one or more lines
     * from the rate tariff (having applied any appropriate
     * company specific discounts) depending on whether asked
     * for a rate or a table.
     *
     * @param type $companyId
     * @param type $rateId
     * @param type $shipDate
     * @param type $serviceCode
     * @param type $packagingCode
     * @param type $area
     * @return type
     */
    public function getRateDetails($companyId, $rateId, $serviceCode = '', $shipDate = '', $packagingCode = '', $area = '')
    {
        $SQL = 'SELECT  domestic_rates.rate_id AS rate_id,
                    domestic_rates.service AS service,
                    domestic_rates.packaging_code AS packaging_code,
                    domestic_rates.first -  COALESCE((domestic_rates.first * first_discount)/100,0) AS "first",
                    domestic_rates.others - COALESCE((domestic_rates.others * others_discount)/100,0) AS "others",
                    domestic_rates.notional_weight AS notional_weight,
                    domestic_rates.notional - COALESCE((domestic_rates.notional * notional_discount)/100,0) AS "notional",
                    domestic_rates.area AS area,
                    domestic_rates.from_date AS from_date,
                    domestic_rates.to_date AS to_date
                FROM domestic_rates
                LEFT JOIN domestic_rate_discounts
                    ON domestic_rate_discounts.rate_id = domestic_rates.rate_id
                    AND domestic_rate_discounts.company_id = :companyId
                    AND domestic_rate_discounts.service = domestic_rates.service
                    AND domestic_rate_discounts.packaging_code = domestic_rates.packaging_code
                    AND domestic_rate_discounts.area = domestic_rates.area
                    AND domestic_rate_discounts.from_date <= :fromDate1
                    AND domestic_rate_discounts.to_date >= :toDate1
                WHERE domestic_rates.rate_id = :rateId ';

        $PARAMS = ['companyId' => $companyId,
            'fromDate1' => date('Y-m-d', strtotime($shipDate)),
            'toDate1' => date('Y-m-d', strtotime($shipDate)),
            'rateId' => $rateId,
        ];

        if ($serviceCode > '') {
            $SQL .= 'AND domestic_rates.service = :service ';
            $PARAMS['service'] = $serviceCode;
        }

        if ($packagingCode > '') {
            $SQL .= 'AND domestic_rates.packaging_code = :packagingCode ';
            $PARAMS['packagingCode'] = $packagingCode;
        }

        if ($area > '') {
            $SQL .= 'AND domestic_rates.area = :area ';
            $PARAMS['area'] = $area;
        }

        $PARAMS['fromDate2'] = date('Y-m-d', strtotime($shipDate));
        $PARAMS['toDate2'] = date('Y-m-d', strtotime($shipDate));

        $SQL .= '   AND domestic_rates.from_date <= :fromDate2
                    AND domestic_rates.to_date >= :toDate2
                ORDER BY domestic_rates.service, domestic_rates.packaging_code, domestic_rates.area';

        if ($this->debug) {
            $message = rawToSql($SQL, $PARAMS).';';
            mail('debug@antrim.ifsgroup.com', 'Pricing Analysis', $message);
        }

        return DB::select(DB::raw($SQL), $PARAMS);
    }

    public function getServiceCode($serviceId)
    {
        $serviceCode = '';

        // If ServiceId defined then get Service code for the required service
        if ($serviceId > '') {
            $service = Service::find($serviceId);
            if ($service) {
                $serviceCode = $service->code;
            }
        }

        return $serviceCode;
    }

    /**
     * Returns appropriate line of the tariff for the
     * Given consignment details.
     *
     * @param type $companyId
     * @param type $rateId
     * @param type $shipDate
     * @param type $serviceId
     * @param type $packagingCode
     * @param type $area
     * @return type
     */
    public function getRate($companyId, $rateId, $serviceId, $shipDate, $packagingCode = '', $area = '')
    {
        $serviceCode = '';

        // As Domestic rates use service code instead of service_id, find it
        $service = Service::find($serviceId);
        if ($service) {
            $serviceCode = $service->code;
        }

        // Get Rate
        $rate = $this->getRateDetails($companyId, $rateId, $serviceCode, $shipDate, $packagingCode, $area);

        if ($rate) {
            return $rate['0'];
        }
    }

    /**
     * Given the Rate Id and Company id gets rate and
     * applies any discounts before returning resultant
     * table array.
     *
     * @param type $companyId
     * @param type $rate
     * @param type $shipDate
     * @return type
     */
    public function getRateTable($companyId, $rateId, $serviceCode, $shipDate = '')
    {
        if ($shipDate == '') {
            $shipDate = date('Y-m-d');
        }

        // Get Rate Details for this Rate/ Service
        $rate = $this->getRateDetails($companyId, $rateId, $serviceCode, $shipDate);

        return $rate;
    }

    public function getZones($rate, $shipDate)
    {

        // Get Zones used by this rate
        return $this->distinct()->where('rate_id', $rate->id)
                        ->where('from_date', '<=', $shipDate)
                        ->where('to_date', '>=', $shipDate)
                        ->groupBy('area')
                        ->get()
                        ->pluck('area');
    }

    public function setRateDiscounts($companyId, $rateId, $serviceId, $discount = '0', $effectiveDate = '')
    {
        $rateDiscounts = [];

        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');
        }

        // Get Copy of Rate table so we can make a discount for each record
        $rateDetail = $this->buildQuery(new self(), '', $rateId, $serviceId, $effectiveDate, 'get');
        foreach ($rateDetail as $rate) {
            $rateDiscounts[] = [
                'company_id' => $companyId,
                'rate_id' => $rateId,
                'service' => $rate->service,
                'packaging_code' => $rate->packaging_code,
                'area' => $rate->area,
                'first_discount' => $discount,
                'others_discount' => $discount,
                'notional_discount' => $discount,
                'from_date' => $effectiveDate,
                'to_date' => '2099-12-31',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        if ($rateDiscounts != []) {
            DomesticRateDiscount::insert($rateDiscounts);
        }
    }

    public function deleteRateDiscounts($companyId, $rateId, $serviceId, $effectiveDate = '')
    {
        $this->buildQuery(new DomesticRateDiscount(), $companyId, $rateId, $serviceId, $effectiveDate, 'delete');
    }

    public function clearExistingRate($companyId, $rateId = '', $serviceId = '', $effectiveDate = '')
    {

        // Clear ALL domestic rates that are specific to this company
        $this->buildQuery($this, $companyId, '', '', $effectiveDate, 'delete');
    }

    public function buildQuery($query, $companyId, $rateId, $serviceId, $effectiveDate, $action = 'get')
    {

        // If No effective date set then use today
        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');
        }

        $serviceCode = $this->getServiceCode($serviceId);

        return $query->when($rateId, function ($query) use ($rateId) {
            return $query->where('rate_id', $rateId);
        })
        ->when($companyId, function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })
        // ->when($serviceCode, function ($query) use ($serviceCode) {
        //      return $query->where('service', $serviceCode);
        // })
        ->when($effectiveDate, function ($query) use ($effectiveDate) {
            return $query->where('from_date', '<=', $effectiveDate);
        })
        ->when($effectiveDate, function ($query) use ($effectiveDate) {
            return $query->where('to_date', '>=', $effectiveDate);
        })
        ->$action();
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
    public function doRateUpload($companyId, $serviceId, $rateId, $stdRate, $uploadedRate, $effectiveDate = '')
    {
        // check old and new rates have same structure
        $currentKeys = $this->buildCurrentKeys($stdRate, $companyId);
        $uploadedKeys = $this->buildUploadedKeys($uploadedRate);
        $diff = array_diff(array_keys($currentKeys), array_keys($uploadedKeys));
        if ($diff == []) {
            // Tables Match so build discounts
            $discounts = $this->buildDiscounts($currentKeys, $uploadedKeys, $companyId, $effectiveDate);

            if ($discounts != []) {
                DomesticRateDiscount::insert($discounts);
            }
        } else {
            return 'Tables do not match';
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
        $discounts = $this->buildQuery(new DomesticRateDiscount(), $companyId, $rateId, $serviceId, $effectiveDate, 'get');
        if ($discounts) {
            foreach ($discounts as $discount) {

                // If Rate only just defined or in the future - remove it so we can replace it.
                if ($discount->from_date >= date('Y-m-d')) {
                    $discount->delete();
                }

                // If pre-existing rate - close it.
                if ($discount->from_date->format('Y-m-d') < date('Y-m-d') && $discount->to_date->format('Y-m-d') >= date('Y-m-d')) {
                    $discount->to_date = date('Y-m-d', strtotime($effectiveDate.' -1 day'));
                    $discount->save();
                }
            }
        }
    }

    /**
     * Check Keys of both arrays are identical
     * And if so return an array of discounts.
     *
     * @param type $currentRate
     * @param type $uploadedRate
     * @return type
     */
    public function buildDiscounts($currentKeys, $uploadedKeys, $companyId, $effectiveDate = '')
    {
        $discounts = [];
        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');                                     // If not specified then effective today
        }
        foreach ($currentKeys as $key => $current) {

            // Build discount details for this record
            $discount['company_id'] = $companyId;
            $discount['rate_id'] = $current['rate_id'];
            $discount['service'] = $current['service'];
            $discount['packaging_code'] = $current['packaging_code'];
            $discount['area'] = $current['area'];
            $discount['first_discount'] = calcDiscPercentage($current['first'], $uploadedKeys[$key]['first']);
            $discount['others_discount'] = calcDiscPercentage($current['others'], $uploadedKeys[$key]['others']);
            $discount['notional_discount'] = calcDiscPercentage($current['notional'], $uploadedKeys[$key]['notional']);
            $discount['from_date'] = $effectiveDate;
            $discount['to_date'] = '2099-12-31';

            // Only store discount if one of the values is non zero
            if ($discount['first_discount'] != 0 || $discount['others_discount'] != 0 || $discount['notional_discount'] != 0) {
                $discounts[] = $discount;
            }
        }

        return $discounts;
    }

    /**
     * Given rate details, converts rate into an array
     * with a composite key of the key fields.
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
            $keyFields = ['service', 'packaging_code', 'area'];
            foreach ($keyFields as $keyField) {
                if (isset($row->$keyField)) {
                    $key .= $row->$keyField;
                }
            }

            if ($key > '') {
                $keys[$key]['rate_id'] = $row->rate_id;
                $keys[$key]['service'] = $row->service;
                $keys[$key]['packaging_code'] = $row->packaging_code;
                $keys[$key]['area'] = $row->area;
                $keys[$key]['first'] = $row->first;
                $keys[$key]['others'] = $row->others;
                $keys[$key]['notional'] = $row->notional;
                $keys[$key]['from_date'] = $row->from_date;
                $keys[$key]['to_date'] = $row->to_date;
            }
        }

        return $keys;
    }

    /**
     * Given rate details, converts rate into an array
     * with a composite key of the key fields.
     *
     * @param type $rateTable
     * @return type
     */
    public function buildUploadedKeys($rateTable)
    {
        $keys = [];
        foreach ($rateTable as $row) {
            $key = '';
            $keyFields = ['service', 'packaging_code', 'area'];
            foreach ($keyFields as $keyField) {
                if (isset($row[$keyField])) {
                    $key .= $row[$keyField];
                }
            }

            if ($key > '') {
                $keys[$key]['first'] = $row['first'];
                $keys[$key]['others'] = $row['others'];
                $keys[$key]['notional'] = $row['notional'];
            }
        }

        return $keys;
    }
}
