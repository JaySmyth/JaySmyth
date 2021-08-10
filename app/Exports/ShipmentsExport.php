<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShipmentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $shipments;
    public $companyId;

    public function __construct($shipments)
    {
        $this->shipments = $shipments;
        $this->companyId = $this->shipments->first()->company_id;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'Consignment Number',
            'Carrier Consignment Number',
            'Shipment Reference',
            'Pieces',
            'Weight',
            'Volume',
            'Shipping Charge',
            'Customs Value',
            'Customs Currency',
            'Sender Name',
            'Sender Company Name',
            'Sender Address 1',
            'Sender Address 2',
            'Sender Address 3',
            'Sender City',
            'Sender State',
            'Sender Postcode',
            'Sender Country',
            'Sender Telephone',
            'Sender Email',
            'Recipient Name',
            'Recipient Company_name',
            'Recipient Address 1',
            'Recipient Address 2',
            'Recipient Address 3',
            'Recipient City',
            'Recipient State',
            'Recipient Postcode',
            'Recipient Country',
            'Recipient Telephone',
            'Recipient Email',
            'Date Created',
            'Ship Date',
            'Service',
            'Time In Transit',
            'Status',
            'POD Signature',
            'Delivery Date',
            'Tracking',
        ];

        if ($this->companyId != 1090) {
            unset($headings[6]);
        }

        return $headings;
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->shipments as $shipment) {
            $row = collect([
                'Consignment Number' => $shipment->consignment_number,
                'Carrier Consignment Number' => $shipment->carrier_consignment_number,
                'Shipment Reference' => $shipment->shipment_reference,
                'Pieces' => $shipment->pieces,
                'Weight' => $shipment->weight.strtoupper($shipment->weight_uom),
                'Volume' => $shipment->volumetric_weight,
                'Shipping Charge' => $shipment->shipping_charge,
                'Customs Value' => $shipment->customs_value,
                'Customs Currency' => strtoupper($shipment->customs_value_currency_code),
                'Sender Name' => $shipment->sender_name,
                'Sender Company Name' => $shipment->sender_company_name,
                'Sender Address 1' => $shipment->sender_address1,
                'Sender Address 2' => $shipment->sender_address2,
                'Sender Address 3' => $shipment->sender_address3,
                'Sender City' => $shipment->sender_city,
                'Sender State' => $shipment->sender_state,
                'Sender Postcode' => $shipment->sender_postcode,
                'Sender Country' => $shipment->sender_country_code,
                'Sender Telephone' => $shipment->sender_telephone,
                'Sender Email' => $shipment->sender_email,
                'Recipient Name' => $shipment->recipient_name,
                'Recipient Company_name' => $shipment->recipient_company_name,
                'Recipient Address 1' => $shipment->recipient_address1,
                'Recipient Address 2' => $shipment->recipient_address2,
                'Recipient Address 3' => $shipment->recipient_address3,
                'Recipient City' => $shipment->recipient_city,
                'Recipient State' => $shipment->recipient_state,
                'Recipient Postcode' => $shipment->recipient_postcode,
                'Recipient Country' => $shipment->recipient_country_code,
                'Recipient Telephone' => $shipment->recipient_telephone,
                'Recipient Email' => $shipment->recipient_email,
                'Date Created' => $shipment->created_at->timezone('Europe/London')->format('d-m-Y'),
                'Ship Date' => $shipment->ship_date->timezone('Europe/London')->format('d-m-Y'),
                'Service' => $shipment->service->code,
                'Time In Transit' => $shipment->timeInTransit,
                'Status' => $shipment->status->name,
                'POD Signature' => $shipment->pod_signature,
                'Delivery Date' => $shipment->getDeliveryDate('d-m-Y H:i'),
                'Tracking' => url('/tracking/'.$shipment->token),
            ]);

            if ($this->companyId != '1099') {
                $row->forget('Shipping Charge');
            }

            $collection->push($row);
        }

        return $collection;
    }
}
