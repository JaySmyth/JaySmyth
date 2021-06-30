<?php

namespace App\CarrierAPI\ExpressFreight;

use App\CarrierAPI\ExpressFreight\ExpressFreightLabel;
use App\Models\Shipment;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Validator;

/**
 * Description of IFSWebAPI.
 *
 * @author gmcbroom
 */
class ExpressFreightAPI extends \App\CarrierAPI\CarrierBase
{
    /*
     *  Carrier Specific Variable declarations
     */

    public $mode;

    public function preProcess($shipment)
    {
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

    public function validateShipment($shipment)
    {
        /**
         * Validate county
         */
        $v = Validator::make($shipment, [
            'recipient_state' => 'required|starts_with:County ,county ',
        ], [
            'recipient_state.required' => 'Recipient county required. E.g. "County Cork"',
            'recipient_state.starts_with' => 'Please prefix recipient county with "County". E.g. "County Cork"',
        ]);

        if ($v->fails()) {
            return $this->buildValidationErrors($v->errors());
        }

        $rules['dry_ice'] = 'not_supported';
        $rules['insurance_value'] = 'not_supported';
        $rules['sender_country_code'] = 'in:GB,gb';
        $rules['recipient_country_code'] = 'in:IE,ie';

        return $this->applyRules($rules, $shipment);
    }

    public function initCarrier()
    {
    }

    public function buildCarrierShipment($shipment)
    {
        return $shipment;
    }

    private function sendMessageToCarrier($shipment)
    {
        $msgType = 'MSG';

        // Call the IFS service and display the XML result
        $sentTx = json_encode($shipment);

        // Log Message to be sent
        TransactionLog::create(['carrier' => 'exp', 'type' => 'MSG', 'direction' => 'O', 'msg' => $sentTx, 'mode' => $this->mode]);

        // Add additional info eg IFS consignment no
        $response = $this->addAdditionalInfo($shipment);

        // Log Response *
        TransactionLog::create(['carrier' => 'exp', 'type' => $msgType, 'direction' => 'I', 'msg' => json_encode($response), 'mode' => $this->mode]);

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

        if(!empty($shipment['shipment_id'])){
            $data['consignment_number'] = Shipment::find($shipment['shipment_id'])->consignment_number;
        } else {
            $data['consignment_number'] = nextAvailable('CONSIGNMENT');                     // Generate an IFS Consignment Number
        }

        $data['pieces'] = $shipment['pieces'];

        for ($i = 0; $i < $data['pieces']; $i++) {

            // Build the tracking number
            $trackingNumber = nextAvailable('EXPCONSIGNMENT');  // express freight sequence (required at piece level)
            $trackingNumber = str_pad($trackingNumber, 8, 0, STR_PAD_LEFT);
            $trackingNumber .= mod11CheckDigit((string) $trackingNumber);                        // Then add check digit

            $trackingNumber .= strtoupper($shipment['recipient_country_code']);
            $trackingNumber = 'XE'.$trackingNumber;

            $data['packages'][$i]['carrier_tracking_number'] = $trackingNumber;         // Store tracking no for package
            $data['packages'][$i]['barcode'] = $trackingNumber;                         // Store tracking no for package

            if ($i == 0) {
                $data['carrier_consignment_number'] = $trackingNumber;              // Use it for the Carrier number
                $data['carrier_tracking_number'] = $trackingNumber;                     // If 1st Package set master tracking no
            }
        }

        return $data;
    }

    private function calc_routing($shipment)
    {
        return 1;
    }

    private function createShipmentResponse($reply, $serviceCode, $route_id, $shipment)
    {
        $response = $this->generateSuccess();

        $response['route_id'] = $route_id;
        $response['carrier'] = 'ifs';
        $response['ifs_consignment_number'] = $reply['consignment_number'];
        $response['consignment_number'] = $reply['carrier_consignment_number'];
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

    /**
     * @param type $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        $label = new ExpressFreightLabel($shipment, $serviceCode, $labelData);

        return $label->create();
    }
}
