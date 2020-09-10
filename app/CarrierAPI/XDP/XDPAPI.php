<?php

namespace App\CarrierAPI\XDP;

use App\CarrierAPI\XDP\XDP;
use App\Models\Service;

/**
 * Description of XDPAPI.
 *
 * @author gmcbroom
 */
class XDPAPI extends \App\CarrierAPI\CarrierBase
{
    /**
     * @param type $shipment
     * @return type
     */
    public function preProcess($shipment)
    {
        if (empty($shipment['bill_shipping_account']) && strtolower($shipment['bill_shipping']) == 'sender') {
            $shipment['bill_shipping_account'] = Service::find($shipment['service_id'])->account;
        }

        if (empty($shipment['bill_tax_duty_account']) && strtolower($shipment['bill_tax_duty']) == 'sender') {
            $shipment['bill_tax_duty_account'] = Service::find($shipment['service_id'])->account;
        }

        return $shipment;
    }

    /**
     * @param type $shipment
     * @return type
     */
    public function validateShipment($shipment)
    {
        $rules['bill_shipping'] = 'required|in:sender,recipient';
        $rules['bill_tax_duty'] = 'required|in:sender,recipient';
        $rules['bill_shipping_account'] = 'required';
        $rules['alcohol'] = 'not_supported';
        $rules['dry_ice'] = 'not_supported';
        $rules['hazardous'] = 'not_supported';
        $rules['insurance_value'] = 'not_supported';
        $rules['lithium_batteries'] = 'not_supported';

        return $this->applyRules($rules, $shipment);
    }

    /**
     * @param type $shipment
     * @return type
     */
    public function createShipment($shipment)
    {
        $response = [];
        $shipment = $this->preProcess($shipment);

        $errors = $this->validateShipment($shipment);

        if (! empty($errors)) {
            return $this->generateErrorResponse($response, $errors);
        }

        $xdp = new XDP($shipment, $this->mode);
        $reply = $xdp->sendMessage();

        // Request unsuccessful - return errors
        if (isset($reply['errors']) && count($reply['errors']) > 0) {
            return $this->generateErrorResponse($response, $reply['errors']);
        }

        // Ensure we catch invalid carrier consignment number
        if (! isset($reply['carrier_consignment_number'])) {
            $arr['errors'][] = 'Unable to obtain a consignment number from carrier. Please check shipment details.';

            return $this->generateErrorResponse($response, $arr);
        }

        // Request successful -  Calc Routing
        $route_id = $this->calc_routing($shipment);

        // Prepare Response
        $response = $this->createShipmentResponse($reply, $shipment['service_code'], $route_id, $shipment);

        return $response;
    }

    /**
     * @param type $shipment
     * @return int
     */
    private function calc_routing($shipment)
    {
        return 1;
    }

    /**
     * @param type $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        $label = new XDPLabel($shipment, $serviceCode, $labelData);

        return $label->create();
    }

    /**
     * @param type $reply
     * @param type $serviceCode
     * @param type $route_id
     * @param type $imageType
     * @param type $labelSize
     */
    private function createShipmentResponse($reply, $serviceCode, $route_id, $shipment)
    {
        $response = $this->generateSuccess();

        $response['route_id'] = $route_id;
        $response['carrier'] = 'xdp';
        $response['ifs_consignment_number'] = nextAvailable('CONSIGNMENT');
        $response['consignment_number'] = $reply['carrier_consignment_number'];
        $response['volumetric_divisor'] = getVolumetricDivisor('XDP', $serviceCode);       // From Helper functions
        $response['pieces'] = $shipment['pieces'];

        for ($i = 0; $i < $shipment['pieces']; $i++) {
            $response['packages'][$i]['index'] = $i + 1;
            $response['packages'][$i]['carrier_tracking_number'] = $reply['barcode'][$i];
            $response['packages'][$i]['barcode'] = $reply['barcode'][$i];
            $response['packages'][$i]['carrier_tracking_code'] = $reply['barcode'][$i]; //??
            $response['packages'][$i]['sequence_number'] = $i + 1;
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';
        $response['label_base64'][0]['carrier_tracking_number'] = $reply['carrier_consignment_number'];
        $response['label_base64'][0]['base64'] = $this->generatePDF($shipment, $serviceCode, $reply['label_data']);

        return $response;
    }

    /**
     * Get carrier identifier for easypost.
     *
     * @return string
     */
    private function getEasypostCarrier($shipment)
    {
        if (strtoupper($shipment['recipient_country_code']) == 'GB') {
            return 'XDP';
        }

        return 'XDPExpress';
    }
}
