<?php

namespace App\Models\CarrierAPI\ExpressFreightNI;

use App\Models\ExpressFreightGazetteer;

class ExpressFreightNILabel extends \App\Models\CarrierAPI\CarrierLabel
{
    /**
     * Accepts Shipment and Carrier Response data
     * and stores these to use to generate labels.
     */
    public function __construct($shipment = null, $serviceCode = null, $data = null)
    {
        parent::__construct($shipment, $serviceCode, $data);
    }

    /**
     * Takes stored Shipment and Carrier Response data
     * and uses it to create a PDF containing all the
     * necessary labels in the following format :-.
     *
     * No Master label, each Label is a package label
     */
    public function create()
    {
        $pkg = 0;
        foreach ($this->shipment['packages'] as $package) {
            $this->addPage();

            // Outer Box
            $this->pdf->SetXY(3, 13);
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Cell(96, 123, '', 1);

            // Express Freight logo
            $this->pdf->Image(storage_path('app/images/express_freight_logo.png'), 4, 14, 24, 18, 'png');
            $this->pdf->SetFont($this->font, 'B', 14);
            $this->pdf->Text(28, 14, 'Express Freight');
            $this->pdf->SetFont($this->font, 'B', 27);
            $this->pdf->Text(22, 14, '.................');
            $this->pdf->SetFont($this->font, 'BI', 10);
            $this->pdf->Text(20, 27, 'Quality in distribution');

            // Verticle text
            $x = 66;
            $y = 36;
            $this->pdf->SetFont($this->font, 'B', 28);
            $this->pdf->StartTransform();
            $this->pdf->Rotate(90, $x, $y);
            $this->pdf->Text($x, $y, 'EXP');
            $this->pdf->StopTransform();

            // Create Box with Heading
            $x = 77;
            $y = 13;
            $this->pdf->SetXY($x, $y);
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Cell(22, 22, '', 1);

            $bay = $this->getBayNumber($this->shipment['recipient_postcode']);

            if (strlen($bay) > 2) {
                $this->pdf->SetFont($this->font, 'B', 28);
                $this->pdf->Text(78, 17, $bay);
            } else {
                $this->pdf->SetFont($this->font, 'B', 40);
                $this->pdf->Text(78, 14, $bay);
            }

            // Consignment number
            $x = 6;
            $this->pdf->SetFont($this->font, 'B', 8);
            $this->pdf->Text($x, 34, substr($this->data['packages'][$pkg]['barcode'], 0, -3));

            $y = 39;
            $this->pdf->Text(6, $y, 'Attempted');
            $this->pdf->Text(40, $y, $this->data['packages'][$pkg]['barcode']);

            // Barcode
            $this->pdf->write1DBarcode($this->data['packages'][$pkg]['barcode'], 'C128', 12, 43, '', 34, 0.5, $this->getBarcodeStyle(false), 'N');

            $y = 78;
            $this->pdf->Text(6, $y, 'Delivery');
            $this->pdf->Text(40, $y, $this->data['packages'][$pkg]['barcode']);

            // Box for delivery address
            $this->pdf->SetXY(5, 90);
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Cell(92, 40, '', 1);

            // Delivery Address
            $x = 6;
            $y = 92;

            $this->pdf->SetFont($this->font, 'B', 8);
            if (isset($this->shipment['recipient_name']) && ($this->shipment['recipient_name'] > '')) {
                $this->pdf->Text($x, $y, strtoupper($this->shipment['recipient_name'] ? $this->shipment['recipient_name'] : ''));
            }

            if (isset($this->shipment['recipient_company_name']) && ($this->shipment['recipient_company_name'] > '')) {
                $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_company_name'] ? $this->shipment['recipient_company_name'] : ''));
            }

            if (isset($this->shipment['recipient_address1']) && ($this->shipment['recipient_address1'] > '')) {
                $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_address1']));
            }

            if (isset($this->shipment['recipient_address2']) && ($this->shipment['recipient_address2'] > '')) {
                $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_address2']));
            }

            if (isset($this->shipment['recipient_city']) && ($this->shipment['recipient_city'] > '')) {
                $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_city']));
            }

            if (isset($this->shipment['recipient_state']) && ($this->shipment['recipient_state'] > '')) {
                if (isset($this->shipment['recipient_postcode']) && ($this->shipment['recipient_postcode'] > '')) {
                    $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_state'].', '.$this->shipment['recipient_postcode']));
                } else {
                    $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_state']));
                }
            } else {
                if (isset($this->shipment['recipient_postcode']) && ($this->shipment['recipient_postcode'] > '')) {
                    $this->pdf->Text($x, $y += 4.1, strtoupper($this->shipment['recipient_postcode']));
                }
            }

            if (isset($this->shipment['recipient_country_code']) && ($this->shipment['recipient_country_code'] > '')) {
                $this->pdf->Text($x, $y += 4.1, strtoupper(getCountry($this->shipment['recipient_country_code'])));
            }

            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(65, 90, 65, 130); //vertical

            $x = 66;
            $y = 92;
            $this->pdf->SetFont($this->font, 'U', 9);
            $this->pdf->Text($x, $y, 'Dispatch Date');
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 6, date('d/m/Y', strtotime($this->shipment['ship_date'])));
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(65, $y + 5, 97, $y + 5); //horizontal

            $this->pdf->SetFont($this->font, 'U', 9);
            $this->pdf->Text($x, $y += 7, 'Item No.s');
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 6, $package['index'].' of '.$this->shipment['pieces']);
            $this->pdf->SetLineWidth(0.3);
            $this->pdf->Line(65, $y + 5, 97, $y + 5); //horizontal

            $this->pdf->SetFont($this->font, 'U', 9);
            $this->pdf->Text($x, $y += 7, 'Weight (Kg)');
            $this->pdf->SetFont($this->font, '', 9);
            $this->pdf->Text($x, $y += 6, $package['weight']);

            // Display sender
            $this->pdf->SetFont($this->font, 'B', 8);
            $this->pdf->Text(6, 125, 'Sender: '.$this->shipment['sender_company_name']);

            $y = 131;
            $this->pdf->SetFont($this->font, 'B', 9);
            $this->pdf->Text(6, $y, 'Tel - 028 38322100');
            $this->pdf->Text($x, $y, 'Fax - 028 38323005');

            $x = 7;
            $y = 141;
            $this->pdf->SetFont($this->font, 'B', 14);
            $this->pdf->Text($x, $y, 'IFS CONSIGNMENT#: '.$this->data['ifs_consignment_number']);
            $pkg++;
        }

        return $this->output();
    }

    /**
     * Lookup the bay number from the EF gazetteer.
     *
     * @param $postcode
     * @return int
     */
    protected function getBayNumber($postcode)
    {
        $postcode = trim(str_replace(' ', '', $postcode));

        $gaz = ExpressFreightGazetteer::where('postcode', $postcode)->first();

        if ($gaz) {
            return strtoupper($gaz->bay);
        }
    }
}
