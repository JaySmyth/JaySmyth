<?php

namespace App\CarrierAPI\IFS;

use App\Carrier;
//
use App\CarrierAPI\IFS\IFSLabel;
use App\CarrierPackagingType;
use App\Company;
use App\Country;
use App\PackagingType;
use App\Service;
use App\TransactionLog;
use Exception;
use Illuminate\Support\Facades\Validator;
use TCPDI;

/**
 * Description of IFSWebAPI.
 *
 * @author gmcbroom
 */
class IFSAPI extends \App\CarrierAPI\CarrierBase
{
    /*
     *  Carrier Specific Variable declarations
     */

    private $account;
    private $pdf;
    private $tpl;
    public $mode;
    private $client;

    public function initCarrier()
    {
    }

    public function buildCarrierShipment($shipment)
    {
        return $shipment;
    }

    private function extract_errors($errorsFound)
    {
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
        $response = [];
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
        $data['carrier_consignment_number'] = $data['consignment_number'];              // Use it for the Carrier number
        $data['pieces'] = $shipment['pieces'];

        for ($i = 0; $i < $data['pieces']; $i++) {
            $trackingNumber = $data['consignment_number'].sprintf('%04d', $i + 1);    // concatenate consignment no with package no
            $trackingNumber .= mod10CheckDigit($trackingNumber);                        // Then add check digit

            if ($i == 0) {
                $data['carrier_tracking_number'] = $trackingNumber;                     // If 1st Package set master tracking no
            }
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
                $errorMsg = 'Carrier Error : '.((string) $reply['errors']);

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
     * @param type $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        $label = new IFSLabel($shipment, $serviceCode, $labelData);

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
        $response['consignment_number'] = $reply['consignment_number'];
        $response['carrier_consignment_number'] = $reply['carrier_consignment_number'];
        $response['volumetric_divisor'] = getVolumetricDivisor('IFS', $serviceCode);       // From Helper functions

        $response['pieces'] = $reply['pieces'];
        for ($i = 0; $i < $response['pieces']; $i++) {
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
