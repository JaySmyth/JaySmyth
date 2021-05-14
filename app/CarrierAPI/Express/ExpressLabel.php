<?php

namespace App\CarrierAPI\Express;

class ExpressLabel extends \App\CarrierAPI\CarrierLabel
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
        // Set the source data and get the number of pages in the PDF
        $pageCount = $this->pdf->setSourceData(file_get_contents($this->data['labels'][0]));

        // Add Master Label (XDP puts at Start)
        if ($pageCount > $this->shipment['pieces']) {
            $this->importPageFromTemplate(1, 1, 7);
        }

        // Add Package Labels
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $this->importPageFromTemplate($pageNumber, 1, 7);
            $this->customizeLabel();
        }

        return $this->output();
    }


    /**
     * Add customisations to label.
     */
    public function customizeLabel()
    {
    }
}
