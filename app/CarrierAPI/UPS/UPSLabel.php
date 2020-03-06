<?php

namespace App\Models\Models\CarrierAPI\UPS;

class UPSLabel extends \App\Models\Models\CarrierAPI\CarrierLabel
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

        /*
         * Single label
         */
        if (isset($this->data['PackageResults']['TrackingNumber'])) {
            $this->addPage();
            $this->addImage($this->data['PackageResults']['LabelImage']['GraphicImage'], [1, -101, 176, 100]);

            // Customize Label
            $this->customizeLabel();

            return $this->output();
        }

        /*
         * Multiple Labels
         */

        foreach ($this->data['PackageResults'] as $package) {
            $this->addPage();
            $this->addImage($package['LabelImage']['GraphicImage'], [1, -101, 176, 100]);

            // Customize Label
            $this->customizeLabel();
        }

        return $this->output();
    }

    public function customizeLabel()
    {
        $this->addServiceBox(80, 23, 22, 20, $this->serviceCode);
    }
}
