<?php

namespace App\Models\Models\CarrierAPI\TNT;

class TNTLabel extends \App\Models\Models\CarrierAPI\CarrierLabel
{
    public function __construct($shipment = null, $serviceCode = null, $data = null)
    {
        parent::__construct($shipment, $serviceCode, $data);
    }

    public function create()
    {
        for ($piece = 1; $piece <= $this->shipment['pieces']; $piece++) {
            $this->addPage();

            // Set the y coordinate
            $y = 0;

            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Line(0, $y + 1, 210, $y + 1); //horizontal

            $x = 3;

            // TNT logo
            $this->pdf->SetFont($this->font, 'B', 34);
            $this->pdf->Text($x, $y + 2, 'TNT');

            // Market Indicator & Transport
            $this->pdf->SetFont($this->font, 'B', 15);
            $this->pdf->Text($x + 28, $y + 1, $this->data['transport_display']);
            $this->pdf->Text($x + 28, $y + 6, $this->data['hazardous']);
            $this->pdf->Text($x + 28, $y + 11, $this->data['xray_display']);

            $this->pdf->SetFont($this->font, 'B', 46);

            if (strlen($this->data['free_circulation_display']) > 0) {
                // Draw a black cell
                $this->pdf->SetXY($x + 72, $y + 1);
                $this->pdf->Cell(14, 5, '', 0, 2, 'L', 1); //width,height,string
                $this->pdf->SetXY(0, 0);
                $this->pdf->SetTextColor(255, 255, 255); // White font
                $this->pdf->Text($x + 72, $y, $this->data['free_circulation_display']);
                $this->pdf->SetTextColor(0, 0, 0);  // Switch back to black font
            }

            $this->pdf->Text($x + 87, $y, $this->data['sort_split_text']);
            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Line($x + 27, $y + 1, $x + 27, $y + 17); //vertical
            $this->pdf->Line($x + 49, $y + 17, $x + 49, $y + 107); //vertical
            // Consignment Number
            $y = 17;
            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Line(0, $y, 107, $y); //horizontal

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y = $y, 'Con No.');
            $this->pdf->SetFont($this->font, 'B', 20);
            $this->pdf->Text($x, $y += 2, $this->data['consignment_number']);
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 8, 'Piece');
            $this->pdf->Text($x + 25, $y, 'Weight');
            $this->pdf->SetFont($this->font, 'B', 15);
            $this->pdf->Text($x, $y += 3, "$piece of ".$this->shipment['pieces']);
            $this->pdf->Text($x + 25, $y, $this->data['weight'][$piece - 1]);

            // Customer Reference
            $y = 38;
            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Line(0, $y, 52, $y); //horizontal
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Customer Reference');
            $this->pdf->SetFont($this->font, 'B', 9);
            $this->pdf->Text($x, $y += 4, strtoupper($this->shipment['shipment_reference']));
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 4, 'S/R Account No '.$this->data['account_number']);

            // Sender Address
            $y = 50;
            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Line(0, $y, 52, $y); //horizontal
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Sender Address');
            $this->pdf->SetFont($this->font, '', 8);
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['sender_name']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['sender_company_name'] ?: $this->shipment['sender_name']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['sender_address1']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['sender_address2']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['sender_city'].' '.$this->shipment['sender_postcode']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['sender_country_code']));

            // Delivery Address
            $y = 72;
            $this->pdf->SetLineWidth(0.5);
            $this->pdf->Line(0, $y, 52, $y); //horizontal
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Delivery Address');
            $this->pdf->SetFont($this->font, '', 8);
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['recipient_name']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['recipient_company_name'] ?: $this->shipment['recipient_name']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['recipient_address1']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['recipient_address2']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['recipient_city'].' '.$this->shipment['recipient_postcode']));
            $this->pdf->Text($x + 5, $y += 3, strtoupper($this->shipment['recipient_country_code']));

            // Cluster Code
            $y = 95;
            // Draw a black cell
            $this->pdf->SetXY($x + 20, $y);
            $this->pdf->Cell(29, 12, '', 0, 2, 'L', 1); //width,height,string

            $this->pdf->Line(0, $y, 52, $y); //horizontal

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 2, 'Postcode /');
            $this->pdf->Text($x, $y += 4, 'Cluster Code');
            $this->pdf->SetFont($this->font, 'B', 16);
            $this->pdf->SetTextColor(255, 255, 255); // White font
            $this->pdf->Text($x + 21, $y - 3, strtoupper($this->data['cluster_code']));
            $this->pdf->SetTextColor(0, 0, 0);  // Switch back to black font
            // Service
            $x = 53;
            $y = 17;

            // Draw a black cell
            $this->pdf->SetXY($x - 1, $y);
            $this->pdf->Cell(55, 21, '', 0, 2, 'L', 1); //width,height,string

            $this->pdf->SetTextColor(255, 255, 255); // White font
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y = $y, 'Service');
            $this->pdf->SetFont($this->font, 'B', 12);
            $this->pdf->Text($x, $y += 3, $this->data['product']);
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 8, 'Option');
            $this->pdf->SetFont($this->font, 'B', 15);
            $this->pdf->Text($x, $y += 3, $this->data['option']);
            $this->pdf->SetTextColor(0, 0, 0);  // Switch back to black font
            // Origin
            $y = 38;

            $this->pdf->Line(52, $y, 107, $y); //horizontal
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 1, 'Origin');
            $this->pdf->SetFont($this->font, 'B', 15);
            $this->pdf->Text($x + 9, $y + 1, $this->data['depot_code']);
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x + 22, $y = $y, 'Pickup Date');
            $this->pdf->Text($x + 22, $y += 4, $this->data['collection_date']);

            // Routing
            $y = 47;

            $this->pdf->Line(52, $y, 107, $y); //horizontal

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 1, 'Routing');
            $this->pdf->SetFont($this->font, 'B', 24);

            // $y = 193;
            foreach ($this->data['transit_depot'] as $depot) {
                $this->pdf->Text($x + 17, $y, $depot->depotCode);
                $y += 7;
            }

            // Sort
            $y = 84;
            $this->pdf->Line(52, $y, 107, $y); //horizontal
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 3, 'Sort');
            $this->pdf->SetFont($this->font, 'B', 24);
            $this->pdf->Text($x + 11, $y, '');

            // Destination Depot
            $y = 95;
            $this->pdf->Line(52, $y, 107, $y); //horizontal
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 2, 'Dest');
            $this->pdf->Text($x, $y + 3, 'Depot');
            $this->pdf->SetFont($this->font, 'B', 24);
            $this->pdf->Text($x + 11, $y - 1, $this->data['destination_depot_code'].'-'.$this->data['due_day']);

            $x = 3;

            $this->pdf->Line(0, 107, 107, 107); //horizontal

            $this->pdf->write1DBarcode($this->data['barcode'][$piece - 1], 'C128', $x + 10, 112, '', 34, 0.4, $this->getBarcodeStyle(), 'N');
        }

        return $this->output();
    }
}
