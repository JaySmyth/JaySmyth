<?php

namespace App\CarrierAPI\IFS;

use App\Service;
use App\Route;
use App\Mode;
use App\Depot;

class IFSLabel extends \App\CarrierAPI\CarrierLabel {

    /**
     * Accepts Shipment and Carrier Response data
     * and stores these to use to generate labels
     */
    function __construct($shipment = null, $serviceCode = null, $data = null)
    {
        parent::__construct($shipment, $serviceCode, $data);
    }

    /**
     * Takes stored Shipment and Carrier Response data
     * and uses it to create a PDF containing all the 
     * necessary labels in the following format :-
     * 
     * No Master label, each Label is a package label
     */
    public function create()
    {
        $service = Service::find($this->shipment['service_id']);
        $depot = Depot::find($this->shipment['depot_id']);
        $mode = Mode::find($this->shipment['mode_id']);
        $route = Route::find($this->shipment['route_id']);

        $pkg = 0;
        foreach ($this->shipment['packages'] as $package) {

            $this->addPage();

            // Sender Address
            $x = 3;
            $y = 2;
            $this->pdf->SetFont($this->font, '', 8);
            $this->pdf->Text($x, $y, 'Sender:');
            $this->pdf->SetFont($this->font, '', 7);
            if (isset($this->shipment['sender_name']) && ($this->shipment['sender_name'] > ""))
                $this->pdf->Text($x, $y += 3.5, strtoupper($this->shipment['sender_name']));

            if (isset($this->shipment['sender_company_name']) && ($this->shipment['sender_company_name'] > ""))
                $this->pdf->Text($x, $y += 3, strtoupper($this->shipment['sender_company_name']));

            if (isset($this->shipment['sender_address1']) && ($this->shipment['sender_address1'] > ""))
                $this->pdf->Text($x, $y += 3, strtoupper($this->shipment['sender_address1']));

            if (isset($this->shipment['sender_address2']) && ($this->shipment['sender_address2'] > ""))
                $this->pdf->Text($x, $y += 3, strtoupper($this->shipment['sender_address2']));

            if (isset($this->shipment['sender_city']) && ($this->shipment['sender_city'] > ""))
                $this->pdf->Text($x, $y += 3, strtoupper($this->shipment['sender_city'] . ' ' . $this->shipment['sender_postcode']));

            if (isset($this->shipment['sender_country_code']) && ($this->shipment['sender_country_code'] > ""))
                $this->pdf->Text($x, $y += 3, strtoupper(getCountry($this->shipment['sender_country_code'])));

            if (isset($this->shipment['sender_telephone']) && ($this->shipment['sender_telephone'] > "")) {
                $this->pdf->Text($x, $y += 3.5, 'Tel: ' . strtoupper($this->shipment['sender_telephone']));
            }
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(0, 28, 110, 28); //horizontal
            // IFS logo
            $this->pdf->Image('/images/ifs_logo_bw.png', 64, 2, 34.2, 24.8, 'png');

            $x = 3;
            $y = 2;

            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line($x + 49, $y + 26, $x + 49, $y + 69); //vertical
            // Pieces and weight          
            $y = 20;
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 9, 'Piece:');
            $this->pdf->Text($x + 23, $y, 'Weight:');
            $this->pdf->SetFont($this->font, 'B', 15);
            $this->pdf->Text($x, $y += 4, $package['index'] . ' of ' . $this->shipment['pieces']);
            $this->pdf->Text($x + 23, $y, $this->shipment['weight'] . ' ' . strtoupper($this->shipment['weight_uom']));
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(0, 41.5, 110, 41.5); //horizontal
            // Service                
            $x = 53;
            $y = 28;
            // Draw a black cell
            $this->pdf->SetXY($x - 1, $y);
            $this->pdf->Cell(55, 13.5, '', 0, 2, 'L', 1); //width,height,string
            $this->pdf->SetTextColor(255, 255, 255); // White font
            $this->pdf->SetFont($this->font, 'B', 40);
            $this->pdf->Text($x, $y - 2, strtoupper($service->code));
            $this->pdf->SetTextColor(0, 0, 0);  // Switch back to black font
            // DIMS
            $x = 3;
            $y = 42.5;
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'DIMS:');
            $this->pdf->SetFont($this->font, '', 11);
            $this->pdf->Text($x, $y += 4, $package['length'] . ' x ' . $package['width'] . ' x ' . $package['height'] . ' ' . strtoupper($this->shipment['dims_uom']));
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(0, 51.5, 110, 51.5); //horizontal
            // Depot                
            $y = 42.5;
            $x = 53;
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Depot:');
            $this->pdf->SetFont($this->font, 'B', 22);
            $this->pdf->Text($x + 15, $y, $depot->code);

            // Ship Date
            $x = 3;
            $y = 52.5;

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Volumetric Weight:');
            $this->pdf->SetFont($this->font, '', 11);
            $this->pdf->Text($x, $y += 4, $this->shipment['volumetric_weight'] . ' ' . strtoupper($this->shipment['weight_uom']));
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(0, 61, 110, 61); //horizontal
            // Routing                
            $x = 53;
            $y = 52;

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Routing:');
            $this->pdf->SetFont($this->font, 'B', 22);
            $this->pdf->Text($x + 15, $y, $route->code);

            // Ship Date
            $x = 3;
            $y = 61.5;

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Ship Date:');
            $this->pdf->SetFont($this->font, '', 11);
            $this->pdf->Text($x, $y += 4, $this->shipment['ship_date']);
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(0, 71, 110, 71); //horizontal
            // Shipment Reference                
            $x = 53;
            $y = 61.5;

            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y, 'Shipment Reference:');
            $this->pdf->SetFont($this->font, '', 11);
            $this->pdf->Text($x, $y += 4, $this->shipment['shipment_reference']);

            // Delivery Address
            $y = 72;
            $x = 3;

            $this->pdf->SetFont($this->font, 'B', 11);
            $this->pdf->Text($x, $y, 'SHIP TO:');
            $this->pdf->SetFont($this->font, '', 11);
            if (isset($this->shipment['recipient_name']) && ($this->shipment['recipient_name'] > ""))
                $this->pdf->Text($x + 3, $y += 5, strtoupper($this->shipment['recipient_name'] ? $this->shipment['recipient_name'] : ''));

            if (isset($this->shipment['recipient_company_name']) && ($this->shipment['recipient_company_name'] > ""))
                $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_company_name'] ? $this->shipment['recipient_company_name'] : ''));

            if (isset($this->shipment['recipient_address1']) && ($this->shipment['recipient_address1'] > ""))
                $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_address1']));

            if (isset($this->shipment['recipient_address2']) && ($this->shipment['recipient_address2'] > ""))
                $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_address2']));

            if (isset($this->shipment['recipient_city']) && ($this->shipment['recipient_city'] > ""))
                $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_city']));

            if (isset($this->shipment['recipient_state']) && ($this->shipment['recipient_state'] > "")) {
                
                if (isset($this->shipment['recipient_postcode']) && ($this->shipment['recipient_postcode'] > "")) {
                    $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_state'] . ', ' . $this->shipment['recipient_postcode']));
                } else {
                    $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_state']));
                }
            } else {
                
                if (isset($this->shipment['recipient_postcode']) && ($this->shipment['recipient_postcode'] > "")) {
                    $this->pdf->Text($x + 3, $y += 4, strtoupper($this->shipment['recipient_postcode']));
                }
            }

            $this->pdf->SetFont($this->font, 'B', 13);
            if (isset($this->shipment['recipient_country_code']) && ($this->shipment['recipient_country_code'] > ""))
                $this->pdf->Text($x + 3, $y += 4, strtoupper(getCountry($this->shipment['recipient_country_code'])));

            $this->pdf->SetFont($this->font, '', 9);
            if (isset($this->shipment['recipient_telephone']) && ($this->shipment['recipient_telephone'] > ""))
                $this->pdf->Text($x + 63, $y + 1, 'Tel: ' . strtoupper($this->shipment['recipient_telephone']));

            //Consignment number
            $x = 3;
            $y = 109;
            $this->pdf->Line(0, $y, 110, $y); //horizontal
            $this->pdf->SetFont($this->font, 'B', 11);
            $this->pdf->Text($x, $y += 2, 'CONSIGNMENT #:');
            $this->pdf->SetFont($this->font, 'B', 20);
            $this->pdf->Text($x + 36, $y - 2, $this->data['consignment_number']);
            $this->pdf->Line(0, $y + 7, 110, $y + 7); //horizontal
            // Barcode
            $this->pdf->write1DBarcode($this->data['packages'][$pkg]['barcode'], 'C128', 20, 120, '', 30, 0.5, $this->getBarcodeStyle(), 'N');

            $pkg++;
        }

        return $this->output();
    }

}
