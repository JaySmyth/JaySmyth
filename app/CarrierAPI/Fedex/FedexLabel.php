<?php

namespace App\CarrierAPI\Fedex;

class FedexLabel extends \App\CarrierAPI\CarrierLabel {

    /**
     * Accepts Shipment and Carrier Response data
     * and stores these to use to generate labels
     */
    function __construct($shipment = null, $serviceCode = null, $data = null, $routeId = 1, $splitServiceBox = false)
    {
        parent::__construct($shipment, $serviceCode, $data, $routeId, $splitServiceBox);
    }

    /**
     * Takes stored Shipment and Carrier Response data
     * and uses it to create a PDF containing all the 
     * necessary labels in the following format :-
     * 
     * Label 1 is a Master Label, others Package Labels
     */
    public function create()
    {

        // Create Package labels
        foreach ($this->shipment as $awb) {

            if (!isset($masterAdded)) {
                $this->addMasterLabel($awb);
                $masterAdded = true;
            }

            $this->addLabel($awb);
        }

        return $this->output();
    }

    /**
     * Add Package Label
     * 
     * @param type $awb
     */
    public function addLabel($awb)
    {

        $this->addPage();

        $this->pdf->Image('http://' . $this->data . '/' . $awb . '.PNG', 0, 0, 102, 153);
        $this->customizeLabel();
    }

    /**
     * Checks to see if Master Label exists 
     * and if so adds it
     * 
     * @param type $awb
     */
    public function addMasterLabel($awb)
    {

        // If an Auxilliary label exists add as Master Label
        $auxLabelExists = strstr(current(get_headers('http://' . $this->data . '/' . $awb . 'AWB.PNG')), "200");
        if ($auxLabelExists) {
            $this->addPage();

            $this->pdf->Image('http://' . $this->data . '/' . $awb . 'AWB.PNG', 0, 0, 102, 153);
            $this->customizeLabel('MASTER');
        }
    }

    /**
     * Customises the label with any IFS
     * specific items. eg Service box.
     * 
     * @param type $labelType
     */
    public function customizeLabel($labelType = '')
    {


        /*
         * **********************************
         * Customize Label for Fedex UK48
         * **********************************
         */

        if ($labelType == 'MASTER') {

            $this->addServiceBox(81, 130, 22, 20, $this->serviceCode);
        } else {

            switch (strtoupper($this->serviceCode)) {

                case 'USG':
                    $this->addServiceBox(78, 18, 22, 19, $this->serviceCode);
                    break;

                default:
                    $this->addServiceBox(78, 20, 22, 20, $this->serviceCode);
                    break;
            }
        }
    }

}
