<?php

namespace App\Models\CarrierAPI\DHL;

use App\Models\CarrierAPI\DHL\DHLLabel;
use App\Models\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Description of DHLAPI.
 *
 * @author gmcbroom
 */
class DHLAPI extends \App\Models\CarrierAPI\CarrierBase
{
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

        $dhl = new DHL($shipment, $this->mode);

        $reply = $dhl->sendRequest();

        // Request unsuccessful - return errors
        if (! isset($reply['ShipmentResponse']['ShipmentIdentificationNumber']) && isset($reply['ShipmentResponse']['Notification'])) {
            $errors = Arr::pluck($reply['ShipmentResponse']['Notification'], 'Message');

            $errors = $this->cleanErrors($errors);

            return $this->generateErrorResponse($response, $errors);
        }

        // Prepare Response
        $response = $this->createShipmentResponse($reply, $shipment['service_code'], 1, $shipment);

        // Create an easypost tracker
        dispatch(new \App\Jobs\CreateEasypostTracker($response['consignment_number'], 'DHLExpress'));

        return $response;
    }

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
            $shipment['bill_shipping_account'] = Service::find($shipment['service_id'])->account;
        }

        return $shipment;
    }

    /**
     * @param type $shipment
     * @return type
     */
    public function validateShipment($shipment)
    {
        /**
         * Don't allow residential shipments to russia.
         */
        $v = Validator::make($shipment, [
            'recipient_type' => 'required',
            'recipient_country_code' => 'required|not_in:IR,KP,CU',
        ], [
            'recipient_type.not_supported' => 'Residential address not supported',
            'recipient_country_code.not_in' => 'Recipient country not supported. Please contact Courier department',
        ]);

        $v->sometimes('recipient_type', 'not_supported', function ($input) {
            return strtolower($input->recipient_type) == 'r' && strtolower($input->recipient_country_code) == 'ru';
        });

        if ($v->fails()) {
            return $this->buildValidationErrors($v->errors());
        }

        /*
         * Standard validation resumes
         */
        $rules['bill_shipping_account'] = 'required|digits:9';
        $rules['bill_tax_duty_account'] = 'sometimes|digits:9';
        $rules['dry_ice'] = 'not_supported';
        $rules['hazardous'] = 'not_supported';
        $rules['insurance_value'] = 'not_supported';
        $rules['lithium_batteries'] = 'not_supported';

        // Validate Shipment using the rules
        $errors = $this->applyRules($rules, $shipment);

        return $errors;
    }

    /**
     * Clean up DHL errors before returning to the user.
     *
     * @param $errors
     * @return mixed
     */
    protected function cleanErrors($errors)
    {
        foreach ($errors as $key => $value) {
            if (stristr($value, 'Process failure occurred')) {
                unset($errors[$key]);
            }

            if (stristr($value, 'minimum length  ------------ /shipreq:ShipmentRequest/RequestedShipment/InternationalDetail/ExportDeclaration/ExportLineItems/ExportLineItem[0]/CommodityCode')) {
                $errors[$key] = 'Commodity codes required. Please edit commodiy lines and update with harmonized or commodity code.';
            }
        }

        return $errors;
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
        $response['carrier'] = 'DHL';
        $response['ifs_consignment_number'] = nextAvailable('CONSIGNMENT');
        $response['consignment_number'] = $reply['ShipmentResponse']['ShipmentIdentificationNumber'];
        $response['volumetric_divisor'] = getVolumetricDivisor('DHL', $serviceCode);       // From Helper functions
        $response['pieces'] = $shipment['pieces'];

        $i = 0;

        foreach ($reply['ShipmentResponse']['PackagesResult']['PackageResult'] as $package) {
            $response['packages'][$i]['index'] = $package['@number'];
            $response['packages'][$i]['carrier_tracking_number'] = $package['TrackingNumber'];
            $response['packages'][$i]['barcode'] = 'J'.$package['TrackingNumber'];
            $response['packages'][$i]['carrier_tracking_code'] = $package['TrackingNumber'];
            $i++;
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';
        $response['label_base64'][0]['carrier_tracking_number'] = $reply['ShipmentResponse']['ShipmentIdentificationNumber'];
        $response['label_base64'][0]['base64'] = $this->generatePDF($shipment, $serviceCode, $reply['ShipmentResponse']['LabelImage'][0]['GraphicImage']);

        return $response;
    }

    /**
     * @param type $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        $label = new DHLLabel($shipment, $serviceCode, $labelData);

        return $label->create();
    }
}
