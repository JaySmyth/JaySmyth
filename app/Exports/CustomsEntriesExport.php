<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomsEntriesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $customsEntries;
    public $fullDutyAndVat;

    public function __construct($customsEntries, $fullDutyAndVat)
    {
        $this->customsEntries = $customsEntries;
        $this->fullDutyAndVat = $fullDutyAndVat;
    }

    /**
     *
     * @return array
     */
    public function headings(): array
    {
        if ($this->fullDutyAndVat) {
            return [
                'Entry Number',
                'Date',
                'Customer',
                'Reference',
                'Consignment Number',
                'Additional Reference',
                'IFS Job Number',
                'Pieces',
                'Weight (KG)',
                'Commodity Count',
                'Commercial Invoice Value',
                'Commercial Invoice Currency',
                'Customs Value (GBP)',
                'Duty (GBP)',
                'VAT (GBP)',
                'Vendor',
                'CPC',
                'Country Of Origin'
            ];
        } else {
            return [
                'Entry Number',
                'Date',
                'Customer',
                'Reference',
                'Addit. Reference',
                'Consignment Number',
            ];
        }
    }

    /**
     *
     * @return type
     */
    public function collection()
    {
        $collection = collect();

        foreach ($this->customsEntries as $entry):

            $vendor = [];
        $cpc = [];

        foreach ($entry->customsEntryCommodity as $commodity) {
            $vendor[$commodity->vendor] = $commodity->vendor;
            $cpc[$commodity->customsProcedureCode->code] = $commodity->customsProcedureCode->code;
        }

        $cpc = implode('|', $cpc);
        $vendor = implode('|', $vendor);

        if ($this->fullDutyAndVat) {
            $row = collect([
                'Entry Number' => $entry->number,
                'Date' => $entry->date->format('d-m-Y'),
                'Customer' => $entry->company->company_name,
                'Reference' => $entry->reference,
                'Consignment Number' => $entry->consignment_number,
                'Additional Reference' => $entry->additional_reference,
                'IFS Job Number' => $entry->scs_job_number,
                'Pieces' => $entry->pieces,
                'Weight (KG)' => $entry->weight,
                'Commodity Count' => $entry->commodity_count,
                'Commercial Invoice Value' => $entry->commercial_invoice_value,
                'Commercial Invoice Currency' => $entry->commercial_invoice_value_currency_code,
                'Customs Value (GBP)' => $entry->customs_value,
                'Duty (GBP)' => $entry->duty,
                'VAT (GBP)' => $entry->vat,
                'Vendor' => $vendor,
                'CPC' => $cpc,
                'Country Of Origin' => $entry->country_of_origin
            ]);
        } else {
            $row = collect([
                    'Entry Number' => $entry->number,
                    'Date' => $entry->date->format('d-m-Y'),
                    'Customer' => $entry->company->company_name,
                    'Reference' => $entry->reference,
                    'Addit. Reference' => $entry->additional_reference,
                    'Consignment Number' => $entry->consignment_number
                ]);
        }

        $collection->push($row);

        endforeach;

        return $collection;
    }
}
