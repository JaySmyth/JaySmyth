<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DimsExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct($shipments)
    {
        $this->shipments = $shipments;
    }

    /**
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'Sender Company Name',
            'Consignment Number',
            'Carrier Consignment Number',
            'SCS Job Number',
            'Destination Country',
            'Destination Postcode',
            'Pieces',
            'Ship Date',
            'Service',
            'Package No.',
            'Package Length',
            'Package Width',
            'Package Height',
            'Package Weight'
        ];
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->shipments as $shipment) :

            foreach ($shipment->packages as $package) :

                $row = collect([
                    'Sender Company Name' => ($package->index == 1) ? $shipment->sender_company_name : null,
                    'Consignment Number' => ($package->index == 1) ? $shipment->consignment_number : null,
                    'Carrier Consignment Number' => ($package->index == 1) ? $shipment->carrier_consignment_number : null,
                    'SCS Job Number' => $shipment->scs_job_number,
                    'Destination Country' => $shipment->recipient_country_code,
                    'Destination Postcode' => $shipment->recipient_postcode,
                    'Pieces' => ($package->index == 1) ? $shipment->pieces : null,
                    'Ship Date' => ($package->index == 1) ? $shipment->ship_date->timezone('Europe/London')->format('d-m-Y') : null,
                    'Service' => ($package->index == 1) ? $shipment->service->code : null,
                    'Package No.' => $package->index,
                    'Package Length' => $package->length,
                    'Package Width' => $package->width,
                    'Package Height' => $package->height,
                    'Package Weight' => $package->weight . strtoupper($shipment->weight_uom)
                ]);

                $collection->push($row);

            endforeach;

        endforeach;

        return $collection;
    }

}
