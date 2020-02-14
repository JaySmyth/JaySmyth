<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompaniesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct($companies)
    {
        $this->companies = $companies;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Company Name',
            'Address 1',
            'Address 2',
            'Address 3',
            'City',
            'State',
            'Postcode',
            'Country Code',
            'Address Type',
            'Telephone',
            'Email',
            'Site Name',
            'Company Code',
            'SCS Code',
            'Carrier Choice',
            'Vat Exempt',
            'Enabled',
            'Testing',
            'Print Format',
            'Salesperson',
            'Depot',
            'Master Label',
            'Commercial Invoice',
            'Localisation',
            'Date Created',
        ];
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->companies as $company) :

            if ($company->depot_id == 4) {
                continue;
            }

        $row = collect([
                'ID' => $company->id,
                'Company Name' => $company->company_name,
                'Address 1' => $company->address1,
                'Address 2' => $company->address2,
                'Address 3' => $company->address3,
                'City' => $company->city,
                'State' => $company->state,
                'Postcode' => $company->postcode,
                'Country Code' => $company->country_code,
                'Address Type' => $company->address_type,
                'Telephone' => $company->telephone,
                'Email' => $company->email,
                'Site Name' => $company->site_name,
                'Company Code' => $company->company_code,
                'SCS Code' => $company->scs_code,
                'Carrier Choice' => $company->carrier_choice,
                'Vat Exempt' => $company->vat_exempt,
                'Enabled' => $company->enabled,
                'Testing' => $company->testing,
                'Print Format' => $company->printFormat->code,
                'Salesperson' => $company->salesperson,
                'Depot' => $company->depot->code,
                'Master Label' => $company->master_label,
                'Commercial Invoice' => $company->commercial_invoice,
                'Localisation' => $company->localisation->time_zone,
                'Date Created' => $company->created_at,
            ]);

        $collection->push($row);

        endforeach;

        return $collection;
    }
}
