<?php

namespace App\CarrierAPI\PrimaryFreight;

/**
 * @author gmcbroom
 */
class PrimaryFreightAPI extends \App\Models\CarrierAPI\CarrierBase
{
    /**
     * @param type $shipment
     * @return type
     */
    public function preProcess($shipment)
    {
        return $shipment;
    }

    /**
     * @param type $shipment
     * @return type
     */
    public function validateShipment($shipment)
    {
        $rules['alcohol'] = 'not_supported';
        $rules['dry_ice'] = 'not_supported';
        $rules['hazardous'] = 'not_supported';
        $rules['lithium_batteries'] = 'not_supported';

        return $this->applyRules($rules, $shipment);
    }

    /**
     * @param type $shipment
     * @return type
     */
    public function createShipment($shipment)
    {
        $shipment = $this->preProcess($shipment);
        $errors = $this->validateShipment($shipment);
        if (! empty($errors)) {
            $this->log('REPLY', 'I', json_encode($errors));

            return $this->generateErrorResponse($response, $errors);
        }
        $response = $this->createShipmentResponse([], $shipment['service_code'], 1, $shipment);
        $this->log('REPLY', 'I', json_encode($response));

        return $response;
    }

    /**
     * @param type $reply
     */
    private function generatePdf($shipment, $serviceCode, $labelData)
    {
        return '';
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

        $consignment = nextAvailable('CONSIGNMENT');

        $response['route_id'] = $route_id;
        $response['carrier'] = 'US Domestic Carrier';
        $response['ifs_consignment_number'] = $consignment;
        $response['consignment_number'] = $consignment;
        $response['volumetric_divisor'] = 5000;
        $response['pieces'] = $shipment['pieces'];

        for ($i = 0; $i < $shipment['pieces']; $i++) {
            $bc = $consignment.$i.time();
            $response['packages'][$i]['index'] = $i + 1;
            $response['packages'][$i]['carrier_tracking_number'] = $bc;
            $response['packages'][$i]['barcode'] = $bc;
            $response['packages'][$i]['carrier_tracking_code'] = $bc;
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';
        $response['label_base64'][0]['carrier_tracking_number'] = $consignment;
        $response['label_base64'][0]['base64'] = $this->generatePDF($shipment, $serviceCode, $response);

        return $response;
    }

    /**
     * Create a transaction log.
     *
     * @param type $type
     * @param type $direction
     * @param type $msg
     */
    protected function log($type, $direction, $msg)
    {
        \App\Models\TransactionLog::create([
            'type' => $type,
            'carrier' => 'pri',
            'direction' => $direction,
            'msg' => $msg,
        ]);
    }
}
