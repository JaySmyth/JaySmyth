<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomsEntriesByCommodityExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct($customsEntries)
    {
        $this->customsEntries = $customsEntries;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Entry Number',
            'Date',
            'Customer',
            'Reference',
            'Consignment Number',
            'Additional Reference',
            'IFS Job Number',
            'Country Of Origin',
            'Commodity Code',
            'Vendor',
            'Item Value',
            'Duty',
            'Duty Percent',
            'VAT',
            'Nett Weight',
            'CPC',
        ];
    }

    /**
     * @return type
     */
    public function collection()
    {
        $collection = collect();

        foreach ($this->customsEntries as $entry):

            foreach ($entry->customsEntryCommodity as $commodity):

                $row = collect([
                    'Entry Number' => $entry->number,
                    'Date' => $entry->date->format('d-m-Y'),
                    'Customer' => $entry->company->company_name,
                    'Reference' => $entry->reference,
                    'Consignment Number' => $entry->consignment_number,
                    'Additional Reference' => $entry->additional_reference,
                    'IFS Job Number' => $entry->scs_job_number,
                    'Country Of Origin' => ($entry->country_of_origin) ? $entry->country_of_origin : $commodity->country_of_origin,
                    'Commodity Code' => $commodity->commodity_code,
                    'Vendor' => $commodity->vendor,
                    'Item Value' => $commodity->value,
                    'Duty' => $commodity->duty,
                    'Duty Percent' => $commodity->duty_percent,
                    'VAT' => $commodity->vat,
                    'Nett Weight' => $commodity->weight,
                    'CPC' => $commodity->customsProcedureCode->code,
                ]);

        $collection->push($row);

        endforeach;

        endforeach;

        return $collection;
    }
}
