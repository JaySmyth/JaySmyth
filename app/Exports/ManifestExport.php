<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ManifestExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct($manifest, $user)
    {
        $this->manifest = $manifest;
        $this->user = $user;
    }

    /**
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'Consignment Number',
            'Carrier Consignment Number',
            'Shipment Reference',
            'Shipper',
            'Sender Name',
            'Sender Telephone',
            'Sender Email',
            'Destination',
            'Service',
            'Carrier Service',
            'Ship Date',
            'Pieces',
            'Weight',
        ];
    }

    /**
     * 
     * @return type
     */
    public function collection()
    {
        $collection = collect();

        foreach ($this->manifest->shipments as $shipment):

            $row = collect([
                'Consignment Number' => $shipment->consignment_number,
                'Carrier Consignment Number' => $shipment->carrier_consignment_number,
                'Shipment Reference' => $shipment->shipment_reference,
                'Shipper' => $shipment->company->company_name,
                'Sender Name' => $shipment->sender_name,
                'Sender Telephone' => $shipment->sender_telephone,
                'Sender Email' => $shipment->sender_email,
                'Destination' => $shipment->recipient_city . ', ' . $shipment->recipient_country_code,
                'Service' => $shipment->service->name,
                'Carrier Service' => $shipment->service->carrier_code,
                'Ship Date' => $shipment->ship_date->format($this->user->date_format),
                'Pieces' => $shipment->pieces,
                'Weight' => $shipment->weight . strtoupper($shipment->weight_uom),
            ]);

            $collection->push($row);

        endforeach;

        return $collection;
    }

}
