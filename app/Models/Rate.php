<?php

namespace App\Models;

use App\Exports\RatesExport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Rate extends Model
{
    public function details()
    {
        if (strtolower($this->model) == 'domestic') {
            return $this->hasMany(DomesticRate::class)
                ->orderBy('area');
        }

        return $this->hasMany(RateDetail::class)
            ->orderBy('package_type')
            ->orderBy('zone')
            ->orderBy('break_point')
            ->orderBy('piece_limit');
    }

    public function getRateDetails($rateId, $service, $packagingType, $zone, $pieces, $weight, $shipDate)
    {
        return self::find($rateId)
            ->details()
            ->hasPackageType($packagingType)
            ->hasZone($zone)
            ->hasPieces($pieces)
            ->hasWeight($weight)
            ->where('from_date', '<=', $shipDate)
            ->where('to_date', '>=', $shipDate)
            ->first();
    }

    public function getRateView($company, $service, $discount, $shipDate = null, $viewFormat = 'html')
    {
        $companyRates = new CompanyRates();
        $companyRates->getRateTable($company->id, $this, $service, $shipDate);
        $rateTable = $companyRates->rateTable;
        $zones = $companyRates->zones;
        $tableFormat = $companyRates->tableFormat;
        $table = $companyRates->formatRateTable($this, $discount, $rateTable);

        if ($table) {
            switch ($viewFormat) {

                case 'data':

                    return $this->getRateAsArray($tableFormat, $table, $zones);
                    break;

                default:

                    $rate=$this;
                    $charges = [];
                    if (strtolower($this->rate_type) == 's' && isset($service->sales_surcharge_id)) {
                        $charges = $this->getSurcharges($service->sales_surcharge_id, '', $company->id);
                    } else {
                        if (isset($service->sales_surcharge_id)) {
                            $charges = $this->getSurcharges($service->costs_surcharge_id, '', $company->id);
                        }
                    }

                    if (isset($service->id)) {
                        $companyService = \App\Models\CompanyService::where('company_id', $company->id)->where('service_id', $service->id)->first();
                    } else {
                        $companyService = null;
                    }

                    return view(
                        'rates.show_'.$tableFormat,
                        compact('tableFormat', 'rate', 'table', 'zones', 'charges', 'companyService')
                    );
                    break;
            }
        }

        return view('errors.404');
    }

    public function getRateAsArray($tableformat, $table, $zones)
    {
        switch ($tableformat) {
            case 'domestic':
                return $this->getDomesticRateAsArray($table, $zones);
                break;

            default:
                return $this->getIntlRateAsArray($table, $zones);
                break;
        }
    }

    public function getDomesticRateAsArray($table, $zones)
    {
        $data = [];
        foreach ($table as $row) {
            $tmp['service'] = $row->service;
            $tmp['packaging_code'] = $row->packaging_code;
            $tmp['notional_weight'] = $row->notional_weight;
            $tmp['area'] = $row->area;
            $tmp['first'] = $row->first;
            $tmp['others'] = $row->others;
            $tmp['notional'] = $row->notional;
            $data[] = $tmp;
        }

        return $data;
    }

    public function getIntlRateAsArray($table, $zones)
    {
        $tableRows = [];
        $customerTypes = ['0' => 'N', '1' => 'Y'];
        $rateTypes = ['weight_rate', 'package_rate', 'consignment_rate'];
        $suffix = ['weight_rate' => '/'.$this->weight_units, 'package_rate' => '/ea', 'consignment_rate' => ''];

        // Extract Residential/ Commercial Tables
        foreach ($table as $residential => $rateTable) {

            // Extract tables for each piecelimit breakpoint (single/ multi piece tables)
            foreach ($rateTable as $pieceLimit => $packageDetails) {

                // Extract tables for each package type
                foreach ($packageDetails as $packageType => $breakpointDetails) {

                    // Extract table for package type
                    foreach ($breakpointDetails as $breakpoint => $lineDetails) {

                        // Build a row of rates
                        $rateType = '';
                        foreach ($lineDetails as $zone => $rateDetails) {
                            $data['residential'] = $customerTypes[$residential];
                            $data['piece_limit'] = $pieceLimit;
                            $data['package_type'] = $packageType;
                            $data['break_point'] = $breakpoint;
                            $data['zone_'.$zone] = $rateDetails['value'];
                        }
                        $tableRows[] = $data;
                    }
                }
            }
        }

        return $tableRows;
    }

    /**
     * Download rate - excel.
     *
     * @param  Company  $company
     * @param  Service  $service
     * @param  type  $effectiveDate
     *
     * @return Excel document
     */
    public function downloadMasterRate($effectiveDate = '', $download = true)
    {
        $effectiveDate = ($effectiveDate) ? $effectiveDate : Carbon::today()->toDateString();
        $rate = $this;
        if ($rate) {
            if ($rate->model == 'domestic') {
                $data = DomesticRate::select('rate_id', 'service', 'packaging_code', 'first', 'others', 'notional_weight', 'notional', 'area', 'from_date', 'to_date')
                        ->where('rate_id', $rate->id)
                        ->where('from_date', '<=', $effectiveDate)
                        ->where('to_date', '>=', $effectiveDate)
                        ->orderBy('service')
                        ->orderBy('packaging_code')
                        ->orderBy('area')
                        ->get()
                        ->toArray();
                if (! $data) {
                    $data = $this->getSampleDomestic();
                }

                // Custom formating
                for ($i=0;$i<count($data);$i++) {
                    $data[$i]['from_date'] = substr($data[$i]['from_date'], 0, 10);
                    $data[$i]['to_date'] = substr($data[$i]['to_date'], 0, 10);
                }
            } else {
                $data = RateDetail::select('rate_id', 'residential', 'piece_limit', 'package_type', 'zone', 'break_point', 'weight_rate', 'weight_increment', 'package_rate', 'consignment_rate', 'weight_units', 'from_date', 'to_date')
                        ->where('rate_id', $rate->id)
                        ->where('from_date', '<=', $effectiveDate)
                        ->where('to_date', '>=', $effectiveDate)
                        ->orderBy('residential')
                        ->orderBy('piece_limit')
                        ->orderBy('package_type')
                        ->orderBy('zone')
                        ->orderBy('break_point')
                        ->get()
                        ->toArray();
                if (! $data) {
                    $data = $this->getSamplePricing();
                }
                // Custom formating
                for ($i=0;$i<count($data);$i++) {
                    $data[$i]['residential'] = ($data[$i]['residential']) ? "1" : "0";
                    $data[$i]['from_date'] = substr($data[$i]['from_date'], 0, 10);
                    $data[$i]['to_date'] = substr($data[$i]['to_date'], 0, 10);
                }
            }
        }

        if (! empty($data)) {
            if ($download) {
                return Excel::download(
                    new RatesExport($data),
                    'Master-'.$rate->id.'.csv'
                );
            } else {
                return $data;
            }
        }

        if ($download) {
            return view('errors.404');
        } else {
            return [];
        }
    }

    public function getSampleDomestic()
    {
        $sample[] = [
            'rate_id' => $this->id, 'service' => 'UK48', 'packaging_code' => 'Package - Sample', 'first' => '5.25', 'others' => '5.25', 'notional_weight' => '25', 'notional' => '5.25', 'area' => '2', 'from_date' => date('Y-m-d'), 'to_date' => date('Y-m-d', strtotime('Dec 31')),
        ];

        return $sample;
    }

    public function getSamplePricing()
    {
        $sample[] = [
            'rate_id' => $this->id, 'residential' => '0', 'piece_limit' => '99999', 'package_type' => 'Package - Sample', 'zone' => 'A', 'break_point' => '5', 'weight_rate' => '0.00', 'weight_increment' => '1',
            'package_rate' => '0.00', 'consignment_rate' => '5.25',  'weight_units' => 'kg', 'from_date' => date('Y-m-d'), 'to_date' => date('Y-m-d', strtotime('Dec 31')),
        ];

        return $sample;
    }

    /**
     * Download rate - excel.
     *
     * @param  Company  $company
     * @param  Service  $service
     * @param  type  $effectiveDate
     *
     * @return Excel document
     */
    public function downloadCompanyRate($company, $service, $discount = 0, $effectiveDate = '', $download = true)
    {
        if ($service) {
            $effectiveDate = ($effectiveDate) ? $effectiveDate : Carbon::today()->toDateString();
            $rate = $this;
            if ($rate) {
                if ($rate->model == 'domestic') {
                    $data = $this->getRateView($company, '', $discount, $effectiveDate, 'data');
                } else {
                    $data = $this->getRateView($company, $service, $discount, $effectiveDate, 'data');
                }
            }

            if (! empty($data)) {
                if ($download) {
                    return Excel::download(
                        new RatesExport($data),
                        $company->company_name.'-'.strtoupper($service->code).'.csv'
                    );
                } else {
                    return $data;
                }
            }
        }

        if ($download) {
            return view('errors.404');
        } else {
            return [];
        }
    }

    public function processRateUpload($companyId, $serviceId, $rateId, $uploadedRate, $effectiveDate = '')
    {

        // If No effective date set then use today
        if (empty($effectiveDate)) {
            $effectiveDate = date('Y-m-d');
        }

        // Get Appropriate Rate Model
        $rateModel = $this->getRateObject();

        if ($this->model == 'domestic') {

            // Domestic - Close Discounts for all domestic services for this customer
            $rateModel->closeRateDiscounts($companyId, '', '', $effectiveDate);
            $stdRate = $rateModel->getRateTable($companyId, $rateId, '', $effectiveDate);
        } else {

            // International - Close Discounts for this service for this customer
            $rateModel->closeRateDiscounts($companyId, '', $serviceId, $effectiveDate);
            $stdRate = $rateModel->getRateTable($companyId, $rateId, $serviceId, $effectiveDate);
        }

        // Build New Discounts and return
        return $rateModel->doRateUpload($companyId, $serviceId, $rateId, $stdRate, $uploadedRate, $effectiveDate);
    }

    public function getRateObject()
    {
        switch ($this->model) {
            case 'domestic':
                return new DomesticRate();
                break;

            default:
                return new RateDetail();
                break;
        }
    }

    public function surcharge()
    {
        return $this->hasOne(\App\Models\Surcharge::class, 'id', 'surcharge_id');
    }

    public function getSurcharges($surchargeId, $code = '', $companyId = '0', $shipDate = '')
    {
        $surcharge = new Surcharge();

        return $surcharge->getCharges($surchargeId, $code, $companyId, $shipDate);
    }
}
