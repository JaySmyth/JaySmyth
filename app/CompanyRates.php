<?php

namespace App;

use App\Rate;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CompanyRates extends Model
{
    protected $fillable = ['company_id', 'rate_id', 'discount', 'discounted', 'special_discount', 'fuel_cap', 'service_id'];
    public $rateTable;      // Table of Rate, discounted where appropriate
    public $rateDetail;     // RateDetail Object
    public $zones;
    public $tableFormat;

    /**
    * Retrieve Rate Table.
    *
    * Note: Rates are service agnostic but discounts are not
    *      (although using an international table for domestic
    *      shipments will not work due to zone incompatibilities)
    *
    * @param type $companyId
    * @param type Rate
    * @param type $shipDate
    * @param type Service
    *
    */
    public function getRateTable($companyId, $rate, $service = '', $shipDate = '')
    {
        if ($service == '') {
            $serviceId = '';
            $serviceCode = '';
        } else {
            $serviceId = $service->id;
            $serviceCode = $service->code;
        }

        if ($shipDate == '') {
            $shipDate = date('Y-m-d');
        }

        if ($rate->model == 'domestic') {
            $domesticRate = new DomesticRate();
            $this->rateTable = $domesticRate->getRateTable($companyId, $rate->id, $serviceCode, $shipDate);
            $this->zones = $domesticRate->getZones($rate, $shipDate);
            $this->tableFormat = 'domestic';
        } else {
            $intlRate = new RateDetail();
            $this->rateTable = $intlRate->getRateTable($companyId, $rate->id, $serviceId, $shipDate);
            $this->zones = $intlRate->getZones($rate, $shipDate);
            $this->tableFormat = 'intl';
        }
    }

    /**
    * Format table appropriately for output
    *
    * @param type $rate
    * @param type $discount
    * @param type $rateTable
    * @return type
    */
    public function formatRateTable($rate, $discount, $rateTable)
    {
        switch ($rate->model) {
            case 'domestic':
            $table = $this->formatDomesticTable($rateTable, $discount);
            break;

            default:
            $table = $this->formatIntlTable($rateTable, $discount);
            break;
        }

        return $table;
    }

    /**
    * Format Domestic table applying any discounts
    * from the company_rates table
    *
    * @param type $rateTable
    * @param type $discount
    * @return type
    */
    public function formatDomesticTable($rateTable, $discount = 0)
    {
        $table = [];
        $row = [];

        foreach ($rateTable as $item) {
            if ($discount <> 0) {
                $item->first = number_format($item->first - (($item->first * $discount) / 100), 2, '.', '');
                $item->others = number_format($item->others - (($item->others * $discount) / 100), 2, '.', '');
                $item->notional = number_format($item->notional - (($item->notional * $discount) / 100), 2, '.', '');
            } else {
                $item->first = number_format($item->first, 2, '.', '');
                $item->others = number_format($item->others, 2, '.', '');
                $item->notional = number_format($item->notional, 2, '.', '');
            }

            $table[] = $item;
        }

        return $table;
    }

    /**
    * Format Intl table applying any discounts
    * from the company_rates table
    *
    * @param type $rateTable
    * @param type $discount
    * @return type
    */
    public function formatIntlTable($rateTable, $discount)
    {
        $rate = [];
        $table = [];
        foreach ($rateTable as $item) {

            // Test For Weight Rate
            if ($item->consignment_rate == 0 && $item->weight_rate <> 0 && $item->package_rate == 0) {
                $suffix = '/' . $item->weight_units;
                $rate['value'] = number_format(($item->weight_rate - ($item->weight_rate * $discount) / 100), 2, '.', '');
            } else {

                // Test For Package Rate
                if ($item->consignment_rate == 0 && $item->weight_rate == 0 && $item->package_rate <> 0) {
                    $suffix = '/each';
                    $rate['value'] = number_format($item->package_rate - (($item->package_rate * $discount) / 100), 2, '.', '');
                } else {

                    // Must be consignment Rate
                    $suffix = '';
                    if ($item->consignment_rate > 0 && $item->weight_rate == 0 && $item->package_rate == 0) {
                        $rate['value'] = number_format(($item->consignment_rate - ($item->consignment_rate * $discount) / 100), 2, '.', '');
                    } else {
                        $rate['value'] = 0;
                    }
                }
            }

            $rate['weight_units'] = $item->weight_units;
            $rate['from_date'] = $item->from_date;
            $rate['to_date'] = $item->to_date;

            $table[$item->residential][$item->piece_limit][$item->package_type][$item->break_point . $suffix][$item->zone] = $rate;
        }

        return $table;
    }

    public function getServiceCode($serviceId = '')
    {
        $serviceCode = '';

        // If ServiceId defined then get Service code for the required service
        if ($serviceId > "") {
            $service = Service::find($serviceId);
            if ($service) {
                $serviceCode = $service->code;
            } else {
                $service = Service::where('code', $serviceId)->first();
                if ($service) {
                    $serviceCode = $service->code;
                }
            }
        }

        return $serviceCode;
    }

    /**
    * Calc the max and min discount %'s applied to the
    * to a custom rate
    *
    * @param type $companyId
    * @param type $rateId
    * @param type $serviceCode
    * @param type $effectiveDate
    * @param type $queryType
    * @return type
    */
    public function getMinMaxDiscount($companyId, $rateId, $serviceId = '', $effectiveDate = '', $queryType)
    {
        $serviceCode = $this->getServiceCode($serviceId);

        // If date not defined, use today
        if ($effectiveDate == '') {
            $effectiveDate = date('Y-m-d');
        }

        $value = 0;
        $rateType = strtolower(Rate::find($rateId)->model);

        // Build Query
        switch ($rateType) {
            case 'domestic':
                $value = DB::table('domestic_rate_discounts')
                ->where('company_id', $companyId)
                ->where('rate_id', $rateId)
                ->where('service', $serviceCode)
                ->where('from_date', '<=', $effectiveDate)
                ->where('to_date', '>=', $effectiveDate);
            break;

            default:
                $value = DB::table('rate_discounts')
                ->where('company_id', $companyId)
                ->where('rate_id', $rateId)
                ->where('from_date', '<=', $effectiveDate)
                ->where('to_date', '>=', $effectiveDate);
            break;
        }

        // Complete and execute query
        switch (strtolower($queryType)) {
            case 'min':
            if ($rateType == 'domestic') {
                return ($value->min('first_discount')) ?: 0;
            } else {
                return ($value->min('consignment_discount')) ?: 0;
            }
            break;

            default:
            if ($rateType == 'domestic') {
                return ($value->max('first_discount')) ?: 0;
            } else {
                return ($value->max('consignment_discount')) ?: 0;
            }
            break;
        }
    }

    public function getRateDetailObject($rateId)
    {
        $rate = Rate::find($rateId);
        if ($rate) {
            switch (strtolower($rate->model)) {

                case 'domestic':
                    $this->rateDetail = new DomesticRate();
                break;

                default:
                    $this->rateDetail = new RateDetail();
                break;
            }
        }
    }

    /**
    * Checks Domestic & Non domestic and deletes matching rates
    *
    * @param type $companyId
    * @param type $rateId
    * @param type $effectiveDate
    */
    public function clearExistingRate($companyId, $rateId, $serviceId, $effectiveDate = '')
    {

        // Get empty DomesticRate object
        if ($this->rateDetail) {
            $this->rateDetail = new DomesticRate();

            // Delete existing Domestic Rates
            $this->rateDetail->clearExistingRate($companyId, $rateId, $serviceId, $effectiveDate);

            // Get empty Non DomesticRate object
            $this->rateDetail = new RateDetail();

            // Delete existing Non Domestic Rates
            if ($this->rateDetail) {
                $this->rateDetail->clearExistingRate($companyId, $rateId, '', $effectiveDate);
            }
        }
    }

    public function setDiscount($discount = 0, $effectiveDate = '')
    {

        // Get correct empty RateDetail object
        $this->getRateDetailObject($this->rate_id);

        // Add Discounts
        if ($this->rateDetail) {
            $this->rateDetail->setRateDiscounts($this->company_id, $this->rate_id, $this->service_id, $discount, $effectiveDate);
        }
    }

    public function closeRateDiscounts($companyId, $rateId = '', $serviceId = '', $effectiveDate = '')
    {
        // Get correct empty RateDetail object
        $this->getRateDetailObject($rateId);

        // Close Discounts
        if ($this->rateDetail) {
            $this->rateDetail->closeRateDiscounts($companyId, $rateId, $serviceId, $effectiveDate);
        }
    }

    /**
    * Checks Domestic & Non domestic and deletes matching discounts
    *
    * @param type $companyId
    * @param type $rateId
    * @param type $effectiveDate
    */
    public function deleteDiscount($companyId, $rateId = '', $serviceId = '', $effectiveDate = '')
    {

        // Get empty DomesticRate object
        $this->rateDetail = new DomesticRate();

        // Delete existing Domestic Rate Discounts
        if ($this->rateDetail) {
            $this->rateDetail->deleteRateDiscounts($companyId, $rateId, $serviceId, $effectiveDate);
        }

        // Get empty Non DomesticRate object
        $this->rateDetail = new RateDetail();

        // Delete existing Non DomesticRate Discounts
        if ($this->rateDetail) {
            $this->rateDetail->deleteRateDiscounts($companyId, $rateId, $serviceId, $effectiveDate);
        }
    }
}
