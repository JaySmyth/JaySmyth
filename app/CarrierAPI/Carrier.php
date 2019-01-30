<?php

namespace App\CarrierAPI;

use Illuminate\Support\Facades\Validator;

/**
 * Description of DHLWebAPI
 *
 * @author gmcbroom
 */
class Carrier {

    public $siteId;
    public $password;
    public $accounts;
    public $mode;
    public $connection;
    public $labelStockType;
    public $packageTypes;
    public $weightUnits;
    public $dimensionUnits;
    public $logo;
    public $transactionHeader = 'Test Transaction';

    function __construct($mode) {

        /*
         * *****************************************
         * Define fields for Production/ Development
         * *****************************************
         */
        $this->mode = $mode;                                                   // Store in case need it later
        $this->initCarrier();                                                   // Set Carrier Defaults and conversion tables
    }

    function initCarrier() {
        // Carrier Specific Code
    }

    function setVersion() {
        return "IFS_API_1.1";
    }

    function logMsg($data = '', $msg = '', $type = '') { //log a transaction to the database

        /*
          if (isset($data['ShipCompanyName'])) {
          $sender = $data['ShipCompanyName'];
          } elseif (isset($data['ShipContact'])) {
          $sender = $data['ShipContact'];
          } else {
          $sender = '';
          }

          if (isset($data['CneeCompanyName'])) {
          $destination = $data['CneeCompanyName'];
          } elseif (isset($data['CneeContact'])) {
          $destination = $data['CneeContact'];
          } else {
          $destination = '';
          }

          $FX_Log = new FX_Log();
          $FX_Log->SetField('type', $type);
          $FX_Log->SetField('msg', escape_data($msg));
          $FX_Log->SetField('sender', $sender);
          $FX_Log->SetField('destination', $destination);
          $FX_Log->SetField('date', date('d-m-y'));
          $FX_Log->SetField('time', date('H:i:s'));
          $FX_Log->Insert();
         * 
         */
    }

    public function validateShipment($shipment) {

        $errors = [];

        $shipmentValidation = Validator::make($shipment, [
                    'Carrier' => 'required|in:AUTO,FEDEX',
        ]);

        if ($shipmentValidation->fails()) {

            $errors = $this->buildValidationErrors($shipmentValidation->errors());
        }

        return $errors;
    }

    public function buildValidationErrors($messages) {

        foreach ($messages->all() as $message) {
            $errors[] = $message;
        }

        return $errors;
    }

    public function generateErrors($response, $errors) {

        $response['Result'] = "ERROR";
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $response["Errors"][] = $error;
            }
        } else {
            $response["Errors"][] = $errors;
        }

        return $response;
    }

    public function getElement($shipment, $target) {

        $locArr = explode('.', $target);
        $data = '';

        switch (count($locArr)) {

            case '1':
                if (isset($shipment[$locArr[0]])) {
                    $data = $shipment[$locArr[0]];
                }
                break;

            case '2':
                if (isset($shipment[$locArr[0]][$locArr[1]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]];
                }
                break;

            case '3':
                if (isset($shipment[$locArr[0]][$locArr[1]][$locArr[2]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]][$locArr[2]];
                }
                break;

            case '4':
                if (isset($shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]];
                }
                break;

            case '5':
                if (isset($shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]][$locArr[4]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]][$locArr[4]];
                }
                break;
        }

        return $data;
    }

    public function getData($data, $key) {

        // Check to see if data contains a multivalue item
        $pos = strpos($key, '.*.');
        if ($pos !== false) {

            // Pull back Array
            $reply = $this->getElement($data, substr($key, 0, $pos));

            if (is_array($reply)) {
                foreach ($reply as $item) {

                    // Get only the Key value I am looking for
                    $result[] = $item[substr($key, $pos + 3)];
                }
            } else {
                $result[] = '';
            }
        } else {

            // Process as standard
            $result = $this->getElement($data, $key);
        }

        return $result;
    }

    public function getPayorAccount($data, $terms) {

        switch ($terms) {
            case 'SHIPPER':
                $payor = $this->getData($data, 'Shipper.Contact.Account');
                break;

            case 'RECIPIENT':
                $payor = $this->getData($data, 'Recipient.Contact.Account');
                break;

            case 'OTHER':
                $payor = $this->getData($data, 'Other.Contact.Account');
                break;

            default:
                $payor = '';
                break;
        }

        return $payor;
    }

    public function getPayorCountry($data, $terms) {

        switch ($terms) {
            case 'SHIPPER':
                $country = $this->getData($data, 'Shipper.Address.CountryCode');
                break;

            case 'RECIPIENT':
                $country = $this->getData($data, 'Recipient.Address.CountryCode');
                break;

            case 'OTHER':
                $country = $this->getData($data, 'Other.Address.CountryCode');
                break;

            default:
                $country = '';
                break;
        }

        return $country;
    }

    public function generateError($errors, $source) {

        $response['TransactionHeader'] = $this->transactionHeader;
        $response['Result'] = 'ERROR';
        // $response['Notifications']['Source'] = $source;
        $response['Errors'] = $errors;
        // $response['TransactionDetail'] = array('CustomerTransactionId' => $this->transactionHeader);
        $response['Version'] = $this->setVersion();

        return $response;
    }

    public function generateSuccess($source = "API") {

        $response['TransactionHeader'] = $this->transactionHeader;
        $response['Result'] = 'SUCCESS';
        // $response['Notifications']['Source'] = $source;
        $response['Errors'] = [];
        // $response['TransactionDetail'] = array('CustomerTransactionId' => $this->transactionHeader);
        $response['Version'] = $this->setVersion();

        return $response;
    }

    /*
     * *********************************************
     * *********************************************
     * Start of Interface Calls
     * *********************************************
     * *********************************************
     */

    public function checkAddress($address) {
        // Carrier Specific Code
    }

    public function requestPickup($pickup_request) {
        // Carrier Specific Code
    }

    private function createPickupResponse($reply) {
        // Carrier Specific Code
    }

    public function cancelPickup($cancel_request) {
        // Carrier Specific Code
    }

    private function cancelPickupResponse($reply) {
        // Carrier Specific Code
    }

    public function checkAvailServices($shipment) {
        // Carrier Specific Code
    }

    private function createAvailabilityResponse($data) {
        // Carrier Specific Code
    }

    public function createShipment($shipment) {
        // Carrier Specific Code
    }

    private function createShipmentResponse($reply) {
        // Carrier Specific Code
    }

}

?>
