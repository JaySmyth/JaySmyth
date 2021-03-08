<?php

namespace App\CarrierAPI\XDP;

class XDPLabel extends \App\CarrierAPI\CarrierLabel
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
     * Label 1 is a Master Label, others Package Labels
     */
    public function create()
    {
        // Set the source data and get the number of pages in the PDF
        $pageCount = $this->pdf->setSourceData(base64_decode($this->data));

        // Add Master Label (XDP puts at Start)
        if($pageCount > $this->shipment['pieces']){
            $this->importPageFromTemplate(1, 1, 7);
        }

        // Add Package Labels
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $this->importPageFromTemplate($pageNumber, 1, 7);
            $this->customizeLabel();
        }

        return $this->output();
    }

    public function customizeLabel()
    {
        $this->addLongServiceBox(67, 1, 34, 9, $this->serviceCode);
        $x = 5;
        $y = 136;
        $this->pdf->SetFont($this->font, '', 7);
        $this->pdf->Text($x, $y, 'Sender:');
        $this->pdf->SetFont($this->font, '', 7);
        $this->pdf->Text($x + 10, $y, strtoupper($this->shipment['sender_name']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_company_name'] ?: $this->shipment['sender_name']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_address1']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_address2']));
        $this->pdf->Text($x + 10, $y += 3, strtoupper($this->shipment['sender_city'].', '.$this->shipment['sender_postcode'].', '.$this->shipment['sender_country_code']));
    }
}
