<?php

namespace App\CarrierAPI\DX;

class DXLabel extends \App\CarrierAPI\CarrierLabel
{
    /**
     * Accepts Shipment and Carrier Response data
     * and stores these to use to generate labels.
     */
    public function __construct($shipment = null, $serviceCode = null, $data = null)
    {
        parent::__construct($shipment, $serviceCode, $data);
    }


    public function create()
    {
        foreach ($this->data['packages'] as $key => $package) {
            $this->importPageFromTemplate($this->pdf->setSourceData(base64_decode($package['label'])), -5, 22, 185, 210);
            $this->customizeLabel($key + 1);
        }

        return $this->output();
    }

    /**
     * Add customisations to label.
     *
     * @param $piece
     */
    public function customizeLabel($piece)
    {
        $this->addLongServiceBox(55, 3, 42, 16, $this->serviceCode, true);

        $x = 5;
        $y = 3;
        $this->pdf->SetFont($this->font, '', 6);
        $this->pdf->Text($x, $y, 'Sender:');
        $this->pdf->SetFont($this->font, 'B', 6);
        $this->pdf->Text($x + 10, $y, strtoupper($this->shipment['sender_name']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_company_name'] ?: $this->shipment['sender_name']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_address1']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_address2']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_city'].', '.$this->shipment['sender_postcode'].', '.$this->shipment['sender_country_code']));

        $this->pdf->SetLineWidth(0.4);
        $this->pdf->Line($x, $y += 6, 99, $y); //horizontal

        $y = 104;

        $this->pdf->SetLineWidth(0.4);
        $this->pdf->Line($x, $y, 99, $y); //horizontal

        $this->pdf->SetFont($this->font, 'B', 12);
        $this->pdf->Text($x, $y += 10, 'IFS CONSIGNMENT#: '.$this->data['ifs_consignment_number']);
        $this->pdf->SetFont($this->font, 'B', 9);
        $this->pdf->Text($x, $y += 7, 'REFERENCE: '.$this->shipment['shipment_reference']);
        $this->pdf->Text($x, $y += 7, 'PIECE: '.$piece.'/'.$this->shipment['pieces']);
    }
}
