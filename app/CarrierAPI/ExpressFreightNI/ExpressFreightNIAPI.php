<?php

namespace App\CarrierAPI\ExpressFreightNI;

use TCPDI;
use App\TransactionLog;
use Illuminate\Support\Facades\Validator;
use App\CarrierAPI\ExpressFreightNI\ExpressFreightNILabel;

/**
 * Description of IFSWebAPI
 *
 * @author gmcbroom
 */
class ExpressFreightNIAPI extends \App\CarrierAPI\CarrierBase
{
    /*
     *  Carrier Specific Variable declarations
     */

    public $mode;


    function initCarrier()
    {

    }

    public function buildCarrierShipment($shipment)
    {
        return $shipment;
    }

    public function preProcess($shipment)
    {

    }

    public function validateShipment($shipment)
    {
        $rules['dry_ice'] = 'not_supported';
        $rules['insurance_value'] = 'not_supported';

        return $this->applyRules($rules, $shipment);
    }

    private function sendMessageToCarrier($shipment)
    {
        $msgType = 'MSG';

        // Call the IFS service and display the XML result
        $sentTx = json_encode($shipment);

        // Log Message to be sent
        TransactionLog::create(['carrier' => 'ifs', 'type' => 'MSG', 'direction' => 'O', 'msg' => $sentTx, 'mode' => $this->mode]);

        // Add additional info eg IFS consignment no
        $response = $this->addAdditionalInfo($shipment);

        // Log Response
        TransactionLog::create(['carrier' => 'ifs', 'type' => $msgType, 'direction' => 'I', 'msg' => json_encode($response), 'mode' => $this->mode]);

        // Return Response
        return $response;
    }

    /**
     * Accepts Shipment and adds any additional info
     * required eg. ifs_consignment_number, package nos etc.
     *
     * @param array $shipment
     * @return array $data - Additional data - tracking nos, barcodes etc.
     */
    public function addAdditionalInfo($shipment)
    {

        $data['consignment_number'] = nextAvailable('CONSIGNMENT');                     // Generate an IFS Consignment Number
        $data['pieces'] = $shipment['pieces'];

        /*
        Barcode structure is as follows:
        accountcode(I012)
        dispatchdate
        Consignment Number for that day (001)
        item number (001)
        */

        // Get current consignment number of the day
        $currentConsignmentNumber = nextAvailable('EXPNICONSIGNMENT');
        $currentConsignmentNumber = str_pad($currentConsignmentNumber, 3, 0, STR_PAD_LEFT);

        $consignmentNumber = 'I012' . date('dmy', strtotime($shipment['ship_date'])) . $currentConsignmentNumber;

        // Set the carrier consignment and tracking numbers
        $data['carrier_consignment_number'] = $consignmentNumber;
        $data['carrier_tracking_number'] = $consignmentNumber;

        for ($i = 0; $i < $data['pieces']; $i++) {
            $trackingNumber = $consignmentNumber . str_pad($i + 1, 3, 0, STR_PAD_LEFT); // Append the package number
            $data['packages'][$i]['carrier_tracking_number'] = $trackingNumber;         // Store tracking no for package
            $data['packages'][$i]['barcode'] = $trackingNumber;                         // Store tracking no for package
        }

        return $data;
    }

    public function createShipment($shipment)
    {
        $response = [];

        $errors = $this->validateShipment($shipment);

        if (empty($errors)) {

            // Set IFS specific settings
            $this->initCarrier($shipment);

            // Create Carrier Shipment object
            $ifsShipment = $this->buildCarrierShipment($shipment);

            // Send message to Carrier
            $reply = $this->sendMessageToCarrier($ifsShipment, 'create_shipment');

            // Check for errors
            if (isset($reply['errors']) && $reply['errors'] > '') {

                // Request unsuccessful - return errors
                $errorMsg = 'Carrier Error : ' . ((string)$reply['errors']);
                return $this->generateErrorResponse($response, $errorMsg);
            } else {

                // Request successful -  Calc Routing
                $route_id = $this->calc_routing($shipment);

                // Prepare Response
                return $this->createShipmentResponse($reply, $shipment['service_code'], $route_id, $shipment);
            }
        } else {
            return $this->generateErrorResponse($response, $errors);
        }
    }

    private function calc_routing($shipment)
    {
        return 1;
    }

    /**
     *
     * @param type $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        $label = new ExpressFreightNILabel($shipment, $serviceCode, $labelData);
        return $label->create();
    }

    private function createShipmentResponse($reply, $serviceCode, $route_id, $shipment)
    {
        $response = $this->generateSuccess();

        // Add additional data to be returned
        $volWeight = 0;

        $response['route_id'] = $route_id;
        $response['carrier'] = 'ifs';
        $response['ifs_consignment_number'] = $reply['consignment_number'];
        $response['consignment_number'] = $reply['carrier_consignment_number'];
        $response['carrier_consignment_number'] = $reply['carrier_consignment_number'];
        $response['volumetric_divisor'] = getVolumetricDivisor('IFS', $serviceCode);       // From Helper functions

        $response['pieces'] = $reply['pieces'];
        for ($i = 0; $i < $response['pieces']; ++$i) {
            $response['packages'][$i]['sequence_number'] = $i + 1;
            $response['packages'][$i]['carrier_tracking_code'] = $reply['packages'][$i]['carrier_tracking_number'];
            $response['packages'][$i]['barcode'] = $reply['packages'][$i]['carrier_tracking_number'];
            $awbs[] = $response['packages'][$i]['carrier_tracking_code'];
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';
        $response['label_base64'][0]['carrier_tracking_number'] = $reply['carrier_consignment_number'];
        $response['label_base64'][0]['base64'] = $this->generatePDF($shipment, $serviceCode, $response);

        return $response;
    }

}

?>
