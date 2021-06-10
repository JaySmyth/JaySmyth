<?php

namespace App\CarrierAPI\UPS;

use App\Models\Carrier;
use App\Models\PackagingType;
use App\Models\Service;
use App\Models\TransactionLog;
use Ups\Entity\Shipment;

/**
 * Description of UPSAPI.
 *
 * @author gmcbroom
 */
class UPSAPI extends \App\CarrierAPI\CarrierBase
{
    /*
     *  Carrier Specific Variable declarations
     */

    public $mode;
    private $account;

    /**
     * Accepts the Shipment detail array and calcs
     * The correct Global Product code to return.
     *
     * @param  array  $shipment
     *
     * @return string Global Product Code
     */
    public function getGPC($shipment)
    {
        $service_code = $this->getServiceCode($shipment);
        if (isset($this->svc['service'][$service_code])) {
            $service_details = $this->svc['service'][$service_code];

            return $service_details['gpc'];
        }
    }

    /**
     * Accepts the Shipment detail array and calcs
     * The correct Local Product code to return.
     *
     * @param  array  $shipment
     *
     * @return string Local Product Code
     */
    public function getLPC($shipment)
    {
        $service_code = $this->getServiceCode($shipment);
        if (isset($this->svc['service'][$service_code])) {
            $service_details = $this->svc['service'][$service_code];

            return $service_details['lpc'];
        }
    }

    public function createShipment($shipment)
    {
        $response = [];
        $shipment = $this->preProcess($shipment);

        $errors = $this->validateShipment($shipment);
        if (empty($errors)) {
            $this->initCarrier();
            $upsShipment = $this->BuildUPSShipment($shipment);
            $reply = $this->sendMessageToCarrier($upsShipment, 'create_shipment');

            // Check for errors
            if (isset($reply['errors'])) {
                // Request unsuccessful - return errors
                return $this->generateErrorResponse($response, $reply['errors']);
            } else {
                // Request successful -  Calc Routing
                $route_id = $this->calc_routing($shipment);

                //                      Prepare Response
                $response = $this->createShipmentResponse($reply, $shipment['service_code'], $route_id);

                return $response;
            }
        } else {
            return $this->generateErrorResponse($response, $errors);
        }
    }

    public function preProcess($shipment)
    {
        // UPS does not like '&' characters so replace with '+'
        $shipment = json_decode(str_replace('&', '+', json_encode($shipment)), true);

        if (empty($shipment['recipient_company_name'])) {
            $shipment['recipient_company_name'] = $shipment['recipient_name'];
            $shipment['recipient_name'] = '.';
        }

        if (isset($shipment['alcohol']['quantity']) && $shipment['alcohol']['quantity'] > 0) {
            $shipment['errors'][] = 'Carrier does not accept Alcohol';
        }

        return $shipment;
    }

    public function validateShipment($shipment)
    {
        $errors = [];

        // $rules['bill_shipping'] = 'required|in:sender';
        // $rules['bill_tax_duty'] = 'nullable|in:sender';
        $rules['bill_shipping_account'] = 'required|alpha_num:6';
        $rules['bill_tax_duty_account'] = 'nullable|alpha_num:6';
        $rules['alcohol'] = 'not_supported';
        $rules['dry_ice'] = 'not_supported';
        $rules['hazardous'] = 'not_supported';
        $rules['insurance_value'] = 'not_supported';
        $rules['lithium_batteries'] = 'not_supported';

        // Validate Shipment using the rules
        $errors = $this->applyRules($rules, $shipment);

        return $errors;
    }

    public function initCarrier()
    {
        $this->account = [
            'TEST' => [
                // ID to use to connect to UPS
                'id' => 'IFSGlobal',
                // Password to use to connect to UPS
                'pass' => 'tLniwtbP7',
                // Access Key to use to connect to UPS
                'key' => '4D132F25A427F478',
            ],
            'PRODUCTION' => [
                // ID to use to connect to UPS
                'id' => 'IFSGlobal',
                // Password to use to connect to UPS
                'pass' => 'tLniwtbP7',
                // Access Key to use to connect to UPS
                'key' => '4D132F25A427F478',
                // Shipper, Billing and Duty Account numbers
            ],
        ];

        // Billing
        $this->billTo = ['sender' => 'prepaid', 'recipient' => 'consigneeBilled', 'other' => 'billThirdParty'];

        // Define Weight Units
        $this->weightUnits = ['kg' => 'KGS', 'lb' => 'LBS'];        // Get my Carrier Details
        $this->carrier = Carrier::where('code', 'ups')->first();

        // Define DimensionUnit Units
        $this->dimensionUnits = ['cm' => 'CM', 'in' => 'IN'];

        // Define available services
        $services = $this->carrier->services;
        foreach ($services as $svc) {
            $this->svc[$svc->code] = json_decode($svc->carrier_code, true);
        }

        // Define Package types supported
        $packages = Carrier::find($this->carrier->id)->packagingTypes;
        foreach ($packages as $package) {
            $ptype = strtoupper(PackagingType::find($package->packaging_type_id)->code);
            $this->packageTypes[$ptype] = strtoupper($package->code);
        }
    }

    public function buildUPSShipment($shipment)
    {
        // Test a ShipmentRequest using UPS XML API
        $upsShipment = new Shipment();

        // Set shipper
        $shipper = $upsShipment->getShipper();
        $shipper->setShipperNumber($shipment['bill_shipping_account']);
        $shipper->setName($shipment['sender_company_name']);
        $shipper->setAttentionName(! empty($shipment['sender_name']) ? $shipment['sender_name'] : null);

        $shipperAddress = $shipper->getAddress();
        $shipperAddress->setAddressLine1($shipment['sender_address1']);
        $shipperAddress->setAddressLine2($shipment['sender_address2']);

        if (isset($shipment['sender_address3'])) {
            $shipperAddress->setAddressLine3($shipment['sender_address3']);
        }

        $shipperAddress->setPostalCode($shipment['sender_postcode']);
        $shipperAddress->setCity($shipment['sender_city']);
        // $shipperAddress->setStateProvinceCode($shipment['sender_state']);
        $shipperAddress->setCountryCode($shipment['sender_country_code']);
        $shipper->setAddress($shipperAddress);
        $shipper->setEmailAddress($shipment['sender_email']);
        $shipper->setPhoneNumber($shipment['sender_telephone']);
        $upsShipment->setShipper($shipper);

        // From address
        $address = new \Ups\Entity\Address();
        $address->setAddressLine1($shipment['sender_address1']);
        $address->setAddressLine2($shipment['sender_address2']);

        if (isset($shipment['sender_address3'])) {
            $address->setAddressLine3($shipment['sender_address3']);
        }

        $address->setPostalCode($shipment['sender_postcode']);
        $address->setCity($shipment['sender_city']);
        // $address->setStateProvinceCode($shipment['sender_state']);
        $address->setCountryCode($shipment['sender_country_code']);
        $shipFrom = new \Ups\Entity\ShipFrom();
        $shipFrom->setAddress($address);
        $shipFrom->setName($shipment['sender_company_name']);
        $shipFrom->setAttentionName(! empty($shipment['sender_name']) ? $shipment['sender_name'] : null);
        $shipFrom->setCompanyName($shipment['sender_company_name']);
        $shipFrom->setEmailAddress($shipment['sender_email']);
        $shipFrom->setPhoneNumber($shipment['sender_telephone']);
        $upsShipment->setShipFrom($shipFrom);

        // To address
        $address = new \Ups\Entity\Address();
        $address->setAddressLine1($shipment['recipient_address1']);
        $address->setAddressLine2($shipment['recipient_address2']);
        $address->setAddressLine3(! empty($shipment['recipient_address3']) ? $shipment['recipient_address3'] : null);
        $address->setPostalCode($shipment['recipient_postcode']);
        $address->setCity($shipment['recipient_city']);
        if (in_array($shipment['recipient_country_code'], ['US', 'CA'])) {
            $address->setStateProvinceCode($shipment['recipient_state_code']);
        }
        if (in_array($shipment['recipient_country_code'], ['IM'])) {
            $address->setCountryCode('GB');
        } else {
            $address->setCountryCode($shipment['recipient_country_code']);
        }

        $shipTo = new \Ups\Entity\ShipTo();
        $shipTo->setAddress($address);
        $shipTo->setCompanyName($shipment['recipient_company_name']);
        $shipTo->setAttentionName($shipment['recipient_name']);
        $shipTo->setCompanyName($shipment['recipient_company_name']);
        $shipTo->setEmailAddress(! empty($shipment['recipient_email']) ? $shipment['recipient_email'] : null);
        $shipTo->setPhoneNumber($shipment['recipient_telephone']);
        $upsShipment->setShipTo($shipTo);

        // Set service
        $carrier_service_code = Service::find($shipment['service_id'])->carrier_code;
        $upsService = new \Ups\Entity\Service;
        $upsService->setCode($carrier_service_code);
        $upsService->setDescription($upsService->getName());
        $upsShipment->setService($upsService);

        // Set description
        if (isset($shipment['goods_description']) && $shipment['goods_description'] > '') {
            $upsShipment->setDescription(substr($shipment['goods_description'], 0, 50));
        } else {
            $upsShipment->setDescription($shipment['documents_description']);
        }

        // Add Packages
        foreach ($shipment['packages'] as $package) {
            // Add Package
            $upsPackage = new \Ups\Entity\Package();
            $upsPackage->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
            $upsPackage->getPackageWeight()->setWeight($package['weight']);
            $unit = new \Ups\Entity\UnitOfMeasurement;
            $unit->setCode($this->weightUnits[$shipment['weight_uom']]);
            $upsPackage->getPackageWeight()->setUnitOfMeasurement($unit);

            // Set dimensions
            $dimensions = new \Ups\Entity\Dimensions();
            $dimensions->setLength($package['length']);
            $dimensions->setWidth($package['width']);
            $dimensions->setHeight($package['height']);
            $unit = new \Ups\Entity\UnitOfMeasurement;

            $unit->setCode($this->dimensionUnits[$shipment['dims_uom']]);
            $dimensions->setUnitOfMeasurement($unit);
            $upsPackage->setDimensions($dimensions);

            // Add descriptions because it is a package
            $upsPackage->setDescription('');

            // Add this package
            $upsShipment->addPackage($upsPackage);
        }

        // Set Reference Number
        $referenceNumber = new \Ups\Entity\ReferenceNumber;
        $referenceNumber->setCode(\Ups\Entity\ReferenceNumber::CODE_TRANSACTION_REFERENCE_NUMBER);
        $referenceNumber->setValue($shipment['shipment_reference']);
        $upsShipment->setReferenceNumber($referenceNumber);

        // Set payment information
        $upsShipment->setPaymentInformation(new \Ups\Entity\PaymentInformation(
            $this->billTo[$shipment['bill_shipping']],
            (object) ['AccountNumber' => $shipment['bill_shipping_account']]
        ));

        /*
          // Residential Delivery
          if (isset($shipment['recipient_type']) && strtoupper($shipment['recipient_type'] == 'R')) {
          $specialService = new SpecialService();
          $specialService->SpecialServiceType = 'TK';
          $upsShipment->addSpecialService($specialService);
          }
         */

        // Display the XML that will be sent to UPS
        // echo $upsShipment->toXML();
        // dd();
        return $upsShipment;
    }

    private function sendMessageToCarrier($upsShipment, $labelSpecification)
    {
        $labelSpecification = new \Ups\Entity\ShipmentRequestLabelSpecification('GIF');
        $labelSpecification->setStockSizeHeight(6);
        $labelSpecification->setStockSizeWidth(4);
        $labelSpecification->setImageFormatCode('GIF');

        $response = [];
        $msgType = 'MSG';

        try {
            $api = new \Ups\Shipping(
                $this->account[strtoupper($this->mode)]['key'],
                $this->account[strtoupper($this->mode)]['id'],
                $this->account[strtoupper($this->mode)]['pass']
            );

            $confirm = $api->confirm(\Ups\Shipping::REQ_VALIDATE, $upsShipment, $labelSpecification);

            // Log Message to be sent
            $request = $api->getRequest()->getRequest();
            TransactionLog::create(['carrier' => 'ups', 'type' => 'MSG', 'direction' => 'O', 'msg' => $request, 'mode' => $this->mode]);

            // Log Response
            $reply = $api->getResponse()->getResponse();
            TransactionLog::create(['carrier' => 'ups', 'type' => $msgType, 'direction' => 'I', 'msg' => $reply->asXML(), 'mode' => $this->mode]);

            if ($confirm) {
                $response = $api->accept($confirm->ShipmentDigest);
                $json = json_encode($response);
                TransactionLog::create(['carrier' => 'ups', 'type' => 'JSON', 'direction' => 'I', 'msg' => $json, 'mode' => $this->mode]);
                $response = json_decode($json, true);
            }
        } catch (\Exception $e) {
            $response['errors'][] = $e->getMessage();
        }

        return $response;
    }

    private function calc_routing($shipment)
    {
        return 1;
    }

    private function createShipmentResponse($reply, $serviceCode, $route_id, $imageType = 'PDF', $labelSize = '6X4')
    {
        $response = $this->generateSuccess();

        $response['route_id'] = $route_id;
        $response['carrier'] = 'ups';
        $response['ifs_consignment_number'] = nextAvailable('CONSIGNMENT');
        $response['consignment_number'] = $reply['ShipmentIdentificationNumber'];
        $response['volumetric_divisor'] = getVolumetricDivisor('UPS', $serviceCode);       // From Helper functions

        if (isset($reply['PackageResults']['TrackingNumber'])) {
            // Single Piece
            $response['packages'][0]['sequence_number'] = 1;
            $response['packages'][0]['carrier_tracking_code'] = $reply['PackageResults']['TrackingNumber'];
            $response['packages'][0]['barcode'] = $reply['PackageResults']['TrackingNumber'];
            $awbs[] = $response['packages'][0]['carrier_tracking_code'];
            $response['pieces'] = 1;
        } else {
            // Multiple Pieces
            $i = 0;
            foreach ($reply['PackageResults'] as $packageResult) {
                $response['packages'][$i]['sequence_number'] = $i + 1;
                $response['packages'][$i]['carrier_tracking_code'] = $packageResult['TrackingNumber'];
                $response['packages'][$i]['barcode'] = $packageResult['TrackingNumber'];
                $awbs[] = $packageResult['TrackingNumber'];
                $i++;
            }

            $response['pieces'] = $i;
        }

        // Return Labels
        $response['label_format_type'] = 'PDF';
        $response['label_size'] = '6X4';
        $response['label_base64'][0]['carrier_tracking_number'] = $reply['ShipmentIdentificationNumber']; // Master AWB no
        $response['label_base64'][0]['base64'] = $this->generatePDF($reply, $serviceCode);

        return $response;
    }

    private function generatePdf($data, $serviceCode = '')
    {
        $label = new UPSLabel(null, $serviceCode, $data);

        return $label->create();
    }
}
