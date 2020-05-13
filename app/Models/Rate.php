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

                    $rate = $this;
                    $charges = $this->getSurcharges($company->id);

                    return view(
                        'rates.show_'.$tableFormat,
                        compact('tableFormat', 'rate', 'table', 'zones', 'charges')
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

    public function getSurcharges($companyId, $code = '', $shipDate = '')
    {
        $surcharge = new Surcharge();

        return $surcharge->getCharges($this->surcharge_id, $code, $companyId, $shipDate);
    }
}
