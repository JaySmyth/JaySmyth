<?php

namespace App\CarrierAPI\DHL;

class DHLLabel extends \App\CarrierAPI\CarrierLabel
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

        // Add Master Label (DHL puts at end)
        $this->importPageFromTemplate($pageCount);

        // Add Package Labels
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $this->importPageFromTemplate($pageNumber);
            $this->customizeLabel();
        }

        return $this->output();
    }

    public function customizeLabel()
    {
        $this->addLongServiceBox(67, 1, 35, 9, $this->serviceCode);
    }
}
