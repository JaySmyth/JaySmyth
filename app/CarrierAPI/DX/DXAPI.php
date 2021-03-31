<?php

namespace App\CarrierAPI\DX;

use App\Models\Service;
use Illuminate\Support\Facades\Mail;

/**
 * Description of DXAPI.
 *
 * @author gmcbroom
 */
class DXAPI extends \App\CarrierAPI\CarrierBase
{
    /**
     * @param  type  $shipment
     *
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

        $dx = new DX($shipment, $this->mode);

        $reply = $dx->sendRequest();

        // Request unsuccessful - return errors
        if (isset($reply['errors']) && count($reply['errors']) > 0) {
            return $this->generateErrorResponse($response, $reply['errors']);
        }

        // Prepare Response
        $response = $this->createShipmentResponse($reply, $shipment['service_code'], 1, $shipment);

        return $response;
    }

    /**
     * @param  type  $shipment
     *
     * @return type
     */
    public function preProcess($shipment)
    {
        if (empty($shipment['bill_shipping_account']) && strtolower($shipment['bill_shipping']) == 'sender') {
            $shipment['bill_shipping_account'] = Service::find($shipment['service_id'])->account;
        }

        if (empty($shipment['bill_tax_duty_account']) && strtolower($shipment['bill_tax_duty']) == 'sender') {
            $shipment['bill_shipping_account'] = Service::find($shipment['service_id'])->account;
        }

        return $shipment;
    }

    /**
     * @param  type  $shipment
     *
     * @return type
     */
    public function validateShipment($shipment)
    {
        /*
         * Standard validation resumes
         */
        //$rules['bill_shipping_account'] = 'nullable|digits:9';
        //$rules['bill_tax_duty_account'] = 'nullable|digits:9';
        $rules['dry_ice'] = 'not_supported';
        $rules['hazardous'] = 'not_supported';
        $rules['insurance_value'] = 'not_supported';
        $rules['lithium_batteries'] = 'not_supported';

        // Validate Shipment using the rules
        $errors = $this->applyRules($rules, $shipment);

        return $errors;
    }


    /**
     * @param  type  $reply
     * @param  type  $serviceCode
     * @param  type  $route_id
     * @param  type  $imageType
     * @param  type  $labelSize
     */
    private function createShipmentResponse($reply, $serviceCode, $route_id, $shipment)
    {
        $response = $this->generateSuccess();

        $response['route_id'] = $route_id;
        $response['carrier'] = 'DX';
        $response['ifs_consignment_number'] = $reply['ifs_consignment_number'];
        $response['consignment_number'] = $reply['packages'][0]['trackingNumber'];
        $response['volumetric_divisor'] = getVolumetricDivisor('DX', $serviceCode);       // From Helper functions
        $response['pieces'] = $shipment['pieces'];

        foreach ($reply['packages'] as $i => $package) {
            $response['packages'][$i]['sequence_number'] = $i + 1;
            $response['packages'][$i]['carrier_tracking_code'] = $package['trackingNumber'];
            $response['packages'][$i]['barcode'] = $package['trackingNumber'];
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';
        $response['label_base64'][0]['carrier_tracking_number'] = $reply['packages'][0]['trackingNumber'];
        $response['label_base64'][0]['base64'] = $this->generatePDF($shipment, $serviceCode, $reply);

        return $response;
    }

    /**
     * @param  type  $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        $label = new DXLabel($shipment, $serviceCode, $labelData);

        return $label->create();
    }
}
