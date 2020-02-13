<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseInvoiceNegativeVariancesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct($purchaseInvoiceLines)
    {
        $this->purchaseInvoiceLines = $purchaseInvoiceLines;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Consignment',
            'SCS Job Number',
            'Shipper',
            'Consignor',
            'SCS VAT Code',
            'FRT Carrier',
            'FRT IFS',
            'FSC Carrier',
            'FSC IFS',
            'Other Carrier',
            'Other IFS',
            'Total Carrier',
            'Total IFS',
            'Difference',
        ];
    }

    /**
     * @return type
     */
    public function collection()
    {
        $collection = collect();

        foreach ($this->purchaseInvoiceLines as $line):

            $row = collect([
                'Consignment' => $line->carrier_tracking_number,
                'SCS Job Number' => ($line->scs_job_number) ? $line->scs_job_number : 'UNKNOWN',
                'Shipper' => ($line->shipment_id) ? $line->shipment->company->company_name : 'UNKNOWN',
                'Consignor' => ($line->sender_company_name) ? $line->sender_company_name : $line->carrier_service,
                'SCS VAT Code' => $line->scs_vat_code,
                'FRT Carrier' => number_format($line->total_freight, 2),
                'FRT IFS' => number_format($line->total_freight_ifs, 2),
                'FSC Carrier' => number_format($line->total_fuel_surcharge, 2),
                'FSC IFS' => number_format($line->total_fuel_surcharge_ifs, 2),
                'Other Carrier' => number_format($line->total_other_charges, 2),
                'Other IFS' => number_format($line->total_other_charges_ifs, 2),
                'Total Carrier' => number_format($line->total, 2),
                'Total IFS' => number_format($line->total_ifs, 2),
                'Difference' => $line->difference_formatted,
            ]);

        $collection->push($row);

        endforeach;

        return $collection;
    }
}
