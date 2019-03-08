<?php

namespace App\CarrierAPI\TNT;

use App\Service;
use SimpleXMLElement;

class TNT
{

    private $shipment;
    private $conref;
    private $express_connect_url = "http://iconnection.tnt.com:81/ShipperGate2.asp";
    private $express_label_url = 'https://express.tnt.com/expresslabel/documentation/getlabel';
    private $username;
    private $password;

    function __construct($shipment, $mode)
    {
        // Data array passed through
        $this->shipment = $shipment;

        // Random consignment identifier for TNT to use.
        $this->conref = strtoupper(str_random(8));

        // Remove any unwanted characters before generating xml
        $this->removeUnwantedCharactersFromData();

        // Set the correct account number to use
        $this->billShippingAccount = $this->getBillShippingAccount($mode);

        // Define the environment
        switch ($mode) {
            case 'test':
                $this->username = 'ifsglobalT';
                $this->password = 'tnt12345';
                break;

            default :
                $this->username = 'ifsglobal';
                $this->password = 'November12';
                break;
        }

        // Get the service code
        $this->shipment['service'] = Service::find($this->shipment['service_id'])->carrier_code;
    }

    /**
     * Get the account number to use.
     * 
     * @param type $mode
     * @return string
     */
    private function getBillShippingAccount($mode)
    {
        if ($mode == 'test') {
            return '00514432';
        }

        // Provided by the end user
        if (!empty($this->shipment['bill_shipping_account'])) {
            return $this->shipment['bill_shipping_account'];
        }

        // Lookup the account number
        return Service::find($this->shipment['service_id'])->account;
    }

    /**
     * Generates XML and sends it to TNT. Returns an array containing a consignment
     * number and data for generating a label.
     * 
     * @return array
     */
    public function sendMessage()
    {
        // Generate the Express Connect XML
        $expressConnectXml = $this->generateExpressConnectXml();

        // Create a transaction log
        $this->log('MSG-1', 'O', $expressConnectXml);

        // Send the Express Connect XML to TNT server      
        $result = $this->postExpressConnect($expressConnectXml);

        $this->log('REPLY-1', 'I', $result);

        // Obtain the access key from the response
        if (!$access_key = $this->getAccessKey($result)) {
            return $reply['errors'][] = 'Unable to obtain access key from carrier. Please try again.';
        }

        $this->log('MSG-2', 'O', 'GET_RESULT:' . $access_key);

        // Request the result by sending back the access key
        $result = $this->postExpressConnect('GET_RESULT:' . $access_key);

        $this->log('REPLY-2', 'I', $result);

        // Obtain the XML portion of the string returned            
        if (!$xml = $this->getXmlResult($result)) {
            return $reply['errors'][] = 'Invalid reply from carrier. Please try again.';
        }

        // Read the result into a simpleXML object 
        $result = new SimpleXMLElement($xml);

        // Errors found: move them to our reply array
        if (isset($result->ERROR)) {
            foreach ($result->ERROR as $error) {
                $reply['errors'][] = (string) $error->DESCRIPTION;
            }

            return $reply;
        }

        if (!isset($result->CREATE->CONNUMBER)) {
            return $reply['errors'][] = 'Unable to obtain a consignment number from carrier.';
        }

        /*
         * We have successfully obtained a consignment number from the first 2 calls to the TNT server
         * TNT may return a consignment number as an alphanumeric string or a number.
         * "Express Label" requires a numeric only consignment number. If an alphanumeric
         * consignment number is returned, strip the non numeric characters out.                                                
         * 
         */

        $consignmentNumber = preg_replace('/[^0-9]+/', '', $result->CREATE->CONNUMBER);

        if (!is_numeric($consignmentNumber)) {
            return $reply['errors'][] = 'Carrier consignment number is not numeric.';
        }

        // Generate the Express Label XML and pass it the consignment number that we have just obtained
        $expressLabelXml = $this->generateExpressLabelXml($consignmentNumber);

        $this->log('MSG-3', 'O', $expressLabelXml);

        // Send the Express Label XML to TNT server
        $result = $this->postExpressLabel($expressLabelXml);

        $this->log('REPLY-3', 'I', $result);

        // Read the result and move to simpleXML object
        $result = new SimpleXMLElement($result);

        // Errors found, return the reply
        if (isset($result->brokenRules)) {
            foreach ($result->brokenRules as $error) {
                $reply['errors'][] = (string) $error->errorDescription; // Cast the simpleXML object to a string
            }
            return $reply;
        }

        // Read the XML response, moving barcode info into reply array
        foreach ($result->consignment->pieceLabelData as $labelData) {
            $reply['barcode'][] = (string) $labelData->barcode; // Cast the simpleXML object to a string                        
        }

        // Pass the label data back to use when generating the TNT labels
        $reply['label_data'] = $this->setLabelDataArray($result->consignment);
        $reply['carrier_consignment_number'] = $consignmentNumber;

        return $reply;
    }

    /**
     * Strips ampersand from values.
     */
    private function removeUnwantedCharactersFromData()
    {
        foreach ($this->shipment as $key => $value) {
            $this->shipment[$key] = str_replace('&', '', $value);
        }
    }

    /**
     * Get the access key from the TNT Express Connect response.
     * 
     * @param type $result
     * @return boolean
     */
    private function getAccessKey($result)
    {
        $start = stripos($result, 'COMPLETE:');
        if ($start > 0) {
            $start = $start + 9;
            $access_key = substr($result, $start);
            if (is_numeric($access_key)) {
                return $access_key;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the result from the TNT Express Connect response.
     * 
     * @param type $response
     * @return boolean|string
     */
    private function getXmlResult($response)
    {
        $start = stripos($response, '<document>');
        if ($start > 0) {
            return "<?xml version='1.0' standalone='yes'?>" . substr($response, $start);
        }
        return false;
    }

    /**
     * Send express connect XML to tnt.
     * 
     * @param string $string
     * @return type
     */
    private function postExpressConnect($string)
    {
        $string = "xml_in=" . $string; // Append "xml_in=" to beginning of string

        $header = array(
            "POST ShipperGate2.asp HTTP/1.0",
            "Accept: */*",
            "User-Agent: ShipperGate_socket/1.0",
            "Content-type: application/x-www-form-urlencoded",
            "Content-length: " . strlen($string),
            ""
        );

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $this->express_connect_url);                // set url to post to
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($header != '') {
            curl_setopt($ch, CURLOPT_HEADER, 1);            // CURL to output header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  // Header for CURL to output
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);            // CURL NOT to output header
        }

        curl_setopt($ch, CURLOPT_POST, 0);                  // Transmit as POST method
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);

        $result = curl_exec($ch);                           // send!
        curl_close($ch);                                    // close

        return $result;
    }

    /**
     * Send express label XML to tnt.
     * 
     * @param type $string
     * @return type
     */
    private function postExpressLabel($string)
    {
        $ch = curl_init(); // New curl instance
        curl_setopt($ch, CURLOPT_URL, $this->express_label_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ((trim($this->username) != "") && (trim($this->password) != "")) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);

        $is_secure = strpos($this->express_label_url, "https://");
        if ($is_secure === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Build XML for express label call.
     * 
     * @return string
     */
    private function generateExpressConnectXml()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><ESHIPPER></ESHIPPER>');
        $loginNode = $xml->addChild('LOGIN');

        $loginNode->addChild('COMPANY', $this->username);
        $loginNode->addChild('PASSWORD', $this->password);
        $loginNode->addChild('APPID', 'EC');
        $loginNode->addChild('APPVERSION', '2.2');

        $consignmentBatchNode = $xml->addChild('CONSIGNMENTBATCH');
        $senderNode = $consignmentBatchNode->addChild('SENDER');
        $senderNode->addChild('COMPANYNAME', $this->shipment['sender_company_name']);
        $senderNode->addChild('STREETADDRESS1', $this->shipment['sender_address1']);
        $senderNode->addChild('STREETADDRESS2', $this->shipment['sender_address2']);
        $senderNode->addChild('STREETADDRESS3', $this->shipment['sender_address3']);
        $senderNode->addChild('CITY', $this->shipment['sender_city']);
        $senderNode->addChild('PROVINCE', $this->shipment['sender_state']);
        $senderNode->addChild('POSTCODE', $this->shipment['sender_postcode']);
        $senderNode->addChild('COUNTRY', $this->shipment['sender_country_code']);
        $senderNode->addChild('ACCOUNT', $this->billShippingAccount);
        $senderNode->addChild('CONTACTNAME', $this->shipment['sender_name']);
        $senderNode->addChild('CONTACTDIALCODE', substr($this->shipment['sender_telephone'], 0, 4));
        $senderNode->addChild('CONTACTTELEPHONE', substr($this->shipment['sender_telephone'], 4, 9));
        $senderNode->addChild('CONTACTEMAIL', $this->shipment['sender_email']);

        $collectionNode = $senderNode->addChild('COLLECTION');
        $collectionNode->addChild('SHIPDATE', date('d/m/Y', strtotime($this->shipment['collection_date'])));

        $prefcollecttimeNode = $collectionNode->addChild('PREFCOLLECTTIME');
        $prefcollecttimeNode->addChild('FROM', '0900');
        $prefcollecttimeNode->addChild('TO', '1600');
        $altcollecttimeNode = $collectionNode->addChild('ALTCOLLECTTIME');
        $altcollecttimeNode->addChild('FROM');
        $altcollecttimeNode->addChild('TO');
        $collectionNode->addChild('COLLINSTRUCTIONS');
        $consignmentNode = $consignmentBatchNode->addChild('CONSIGNMENT');
        $consignmentNode->addChild('CONREF', $this->conref);
        $detailsNode = $consignmentNode->addChild('DETAILS');
        $receiverNode = $detailsNode->addChild('RECEIVER');
        $receiverNode->addChild('COMPANYNAME', 'IFS Global Logistics');
        $receiverNode->addChild('STREETADDRESS1', 'IFS Logistics Park');
        $receiverNode->addChild('STREETADDRESS2', 'Seven Mile Straight');
        $receiverNode->addChild('STREETADDRESS3');
        $receiverNode->addChild('CITY', 'Antrim');
        $receiverNode->addChild('PROVINCE', 'County Antrim');
        $receiverNode->addChild('POSTCODE', 'BT41 4QE');
        $receiverNode->addChild('COUNTRY', 'GB');
        $receiverNode->addChild('CONTACTNAME', 'IFS Global Logistics');
        $receiverNode->addChild('CONTACTDIALCODE', '4428');
        $receiverNode->addChild('CONTACTTELEPHONE', '94464211');
        $receiverNode->addChild('CONTACTEMAIL', 'courier@antrim.ifsgroup.com');
        $deliveryNode = $detailsNode->addChild('DELIVERY');
        $deliveryNode->addChild('COMPANYNAME', $this->shipment['recipient_company_name'] ?: $this->shipment['recipient_name']);
        $deliveryNode->addChild('STREETADDRESS1', $this->shipment['recipient_address1']);
        $deliveryNode->addChild('STREETADDRESS2', $this->shipment['recipient_address2']);
        $deliveryNode->addChild('STREETADDRESS3', $this->shipment['recipient_address3']);
        $deliveryNode->addChild('CITY', $this->shipment['recipient_city']);
        $deliveryNode->addChild('PROVINCE', $this->shipment['recipient_state']);

        if (strtoupper($this->shipment['recipient_country_code']) == 'IE') {
            $deliveryNode->addChild('POSTCODE', $this->getTntPostcode());
        } else {
            $deliveryNode->addChild('POSTCODE', trim($this->shipment['recipient_postcode']));
        }

        $deliveryNode->addChild('COUNTRY', $this->shipment['recipient_country_code']);
        $deliveryNode->addChild('CONTACTNAME', $this->shipment['recipient_name']);
        $deliveryNode->addChild('CONTACTDIALCODE', trim(substr($this->shipment['recipient_telephone'], 0, 4)));
        $deliveryNode->addChild('CONTACTTELEPHONE', trim(substr($this->shipment['recipient_telephone'], 4, 9)));
        $deliveryNode->addChild('CONTACTEMAIL', $this->shipment['recipient_email']);
        $detailsNode->addChild('CUSTOMERREF', $this->shipment['shipment_reference']);
        $detailsNode->addChild('CONTYPE', $this->getConType());
        $detailsNode->addChild('PAYMENTIND', substr(strtoupper($this->shipment ['bill_shipping']), 0, 1));
        $detailsNode->addChild('ITEMS', $this->shipment['pieces']);
        $detailsNode->addChild('TOTALWEIGHT', $this->shipment['weight']);
        $detailsNode->addChild('TOTALVOLUME', $this->getTotalVolume());
        $detailsNode->addChild('CURRENCY', $this->shipment['customs_value_currency_code']);
        $detailsNode->addChild('GOODSVALUE', 1);
        //$detailsNode->addChild('INSURANCEVALUE', 1);
        //$detailsNode->addChild('INSURANCECURRENCY', $this->shipment['customs_value_currency_code']);
        $detailsNode->addChild('SERVICE', $this->shipment['service']);
        $detailsNode->addChild('OPTION');
        $detailsNode->addChild('DESCRIPTION', $this->getGoodsDescription());
        $detailsNode->addChild('DELIVERYINST', $this->shipment['special_instructions']);

        /*
          if ($this->shipment['hazardous'] && $this->shipment['hazardous'] == 'E' || is_numeric($this->shipment['hazardous'])) {
          $detailsNode->addChild('HAZARDOUS', 'Y');
          $detailsNode->addChild('UNNUMBER', '');
          }
         */

        /*
         * Package detail.
         */
        foreach ($this->shipment['packages'] as $package) {

            $packageNode = $detailsNode->addChild('PACKAGE');
            $packageNode->addChild('ITEMS', $this->getPackageItemCount($package['index']));
            $packageNode->addChild('DESCRIPTION', 'Package ' . $package['index']);
            $packageNode->addChild('LENGTH', $package['length'] / 100);
            $packageNode->addChild('HEIGHT', $package['height'] / 100);
            $packageNode->addChild('WIDTH', $package['width'] / 100);
            $packageNode->addChild('WEIGHT', $package['weight']);

            /*
             * Package contents (only for international shipments).
             */
            if (!isUkDomestic($this->shipment['recipient_country_code']) && !empty($this->shipment['contents'])) {

                foreach ($this->shipment['contents'] as $content) {
                    if (isset($content['package_index']) && $content['package_index'] == $package['index']) {
                        $articleNode = $packageNode->addChild('ARTICLE');
                        $articleNode->addChild('ITEMS', $content['quantity']);
                        $articleNode->addChild('DESCRIPTION', $content['description']);
                        $articleNode->addChild('WEIGHT', $content['unit_weight']);
                        $articleNode->addChild('INVOICEVALUE', $content['unit_value']);
                        $articleNode->addChild('INVOICEDESC', $content['description']);
                        $articleNode->addChild('HTS', $content['harmonized_code']);
                        $articleNode->addChild('COUNTRY', $content['country_of_manufacture']);
                    }
                }
            }
        }

        $activityNode = $xml->addChild('ACTIVITY');
        $createNode = $activityNode->addChild('CREATE');
        $createNode->addChild('CONREF', $this->conref);
        $shipNode = $activityNode->addChild('SHIP');
        $shipNode->addChild('CONREF', $this->conref);
        $printNode = $activityNode->addChild('PRINT');
        $labelNode = $printNode->addChild('LABEL');
        $labelNode->addChild('CONREF', $this->conref);
        //$connoteNode = $activityNode->addChild('CONNOTE');
        // $connoteNode->addChild('CONREF', $this->conref);
        // $manifestNode = $activityNode->addChild('MANIFEST');
        // $manifestNode->addChild('CONREF', $this->conref);

        return $xml->asXML();
    }

    /**
     * Get TNT consignment type - must be either ‘N’ (NonDoc) or ‘D’ (Doc).
     * 
     * @return string
     */
    private function getConType()
    {
        if ($this->shipment['ship_reason'] == 'documents') {
            return 'D';
        }
        return 'N';
    }

    /**
     * Count the number of items in a package.
     * 
     * @param type $packageIndex
     * @return type
     */
    private function getPackageItemCount($packageIndex)
    {
        $items = 0;

        if (isset($this->shipment['contents']) && is_array($this->shipment['contents'])) {
            foreach ($this->shipment['contents'] as $content) {
                if ($content['package_index'] == $packageIndex) {
                    $items ++;
                }
            }
        }

        if ($items <= 0) {
            return 1;
        }
        return $items;
    }

    /**
     * Generate XML for expressLabel request.
     * 
     * @param type $consignmentNumber
     * @return string
     */
    private function generateExpressLabelXml($consignmentNumber)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><labelRequest></labelRequest>');
        $consignmentNode = $xml->addChild('consignment');
        $consignmentNode->addAttribute('key', $this->conref);
        $consignmentIdentityNode = $consignmentNode->addChild('consignmentIdentity');
        $consignmentIdentityNode->addChild('consignmentNumber', $consignmentNumber);
        $consignmentIdentityNode->addChild('customerReference', $this->shipment['shipment_reference']);
        $consignmentNode->addChild('collectionDateTime', date('Y-m-d', strtotime($this->shipment['collection_date'])) . 'T09:00:00');
        $senderNode = $consignmentNode->addChild('sender');
        $senderNode->addChild('name', $this->shipment['sender_name']);
        $senderNode->addChild('addressLine1', $this->shipment['sender_address1']);
        $senderNode->addChild('addressLine2', $this->shipment['sender_address2']);
        $senderNode->addChild('addressLine3', $this->shipment['sender_address3']);
        $senderNode->addChild('town', $this->shipment['sender_city']);
        $senderNode->addChild('exactMatch', 'Y');
        $senderNode->addChild('province', $this->shipment['sender_state']);
        $senderNode->addChild('postcode', $this->shipment['sender_postcode']);
        $senderNode->addChild('country', $this->shipment['sender_country_code']);
        $deliveryNode = $consignmentNode->addChild('delivery');
        $deliveryNode->addChild('name', $this->shipment['recipient_name']);
        $deliveryNode->addChild('addressLine1', $this->shipment['recipient_address1']);
        $deliveryNode->addChild('addressLine2', $this->shipment['recipient_address2']);
        $deliveryNode->addChild('addressLine3', $this->shipment['recipient_address3']);
        $deliveryNode->addChild('town', $this->shipment['recipient_city']);
        $deliveryNode->addChild('exactMatch', 'Y');
        $deliveryNode->addChild('province', $this->shipment['recipient_state']);

        if (strtoupper($this->shipment['recipient_country_code']) == 'IE') {
            $deliveryNode->addChild('postcode', $this->getTntPostcode());
        } else {
            $deliveryNode->addChild('postcode', trim($this->shipment['recipient_postcode']));
        }

        $deliveryNode->addChild('country', $this->shipment['recipient_country_code']);
        $contactNode = $consignmentNode->addChild('contact');
        $contactNode->addChild('name', $this->shipment['recipient_name']);
        $contactNode->addChild('telephoneNumber', trim($this->shipment['recipient_telephone']));
        $contactNode->addChild('emailAddress', $this->shipment['recipient_email']);
        $productNode = $consignmentNode->addChild('product');
        $productNode->addChild('lineOfBusiness', $this->getLineOfBusiness());
        $productNode->addChild('groupId', 0);
        $productNode->addChild('subGroupId', 0);
        $productNode->addChild('id', $this->getProductId());
        $productNode->addChild('type', $this->getConType());
        $productNode->addChild('option');
        $accountNode = $consignmentNode->addChild('account');
        $accountNode->addChild('accountNumber', $this->billShippingAccount);
        $accountNode->addChild('accountCountry', 'GB');
        $consignmentNode->addChild('totalNumberOfPieces', $this->shipment['pieces']);

        /*
         * Package detail.
         */
        foreach ($this->shipment['packages'] as $package) {
            $pieceLineNode = $consignmentNode->addChild('pieceLine');
            $pieceLineNode->addChild('identifier', $package['index']);
            $pieceLineNode->addChild('goodsDescription', $this->getGoodsDescription($package['index']));
            $pieceMeasurementsNode = $pieceLineNode->addChild('pieceMeasurements');
            $pieceMeasurementsNode->addChild('length', $package['length'] / 100);
            $pieceMeasurementsNode->addChild('width', $package['width'] / 100);
            $pieceMeasurementsNode->addChild('height', $package['height'] / 100);
            $pieceMeasurementsNode->addChild('weight', $package['weight']);

            /*
             * Package contents.
             */
            if (!isUkDomestic($this->shipment['recipient_country_code']) && !empty($this->shipment['contents'])) {
                foreach ($this->shipment['contents'] as $content) {
                    if (isset($content['package_index']) && $content['package_index'] == $package['index']) {
                        $piecesNode = $pieceLineNode->addChild('pieces');
                        $piecesNode->addChild('sequenceNumbers', $package['index']);
                        $piecesNode->addChild('pieceReference', $content['description']);
                    }
                }
            } else {
                $piecesNode = $pieceLineNode->addChild('pieces');
                $piecesNode->addChild('sequenceNumbers', $package['index']);
                $piecesNode->addChild('pieceReference', $this->getGoodsDescription());
            }
        }

        return $xml->asXML();
    }

    /**
     * Get a goods description.
     * 
     * @param type $packageIndex
     * @return string
     */
    private function getGoodsDescription($packageIndex = null)
    {
        if ($this->shipment['ship_reason'] == 'documents') {
            return $this->shipment['documents_description'];
        }

        if (isUkDomestic($this->shipment['recipient_country_code'])) {
            return $this->shipment['goods_description'];
        }

        if (!empty($this->shipment['contents'])) {
            foreach ($this->shipment['contents'] as $content) {
                if ($content['package_index'] == $packageIndex) {
                    return $content['description'];
                }
            }
        }

        return 'COMMODITIES';
    }

    /**
     * Map the service to an express label product id.
     * 
     * @return string
     */
    private function getProductId()
    {
        switch ($this->shipment['service']) {
            case '1' :
            case '15D' :
            case '15N' :
                return 'EX';
                break;
            case '15F' :
                return 'XF';
                break;
            case '48N' :
                return 'EC';
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * TNT "line of business".
     * 
     * @return int
     */
    private function getLineOfBusiness()
    {
        if (strtoupper($this->shipment['recipient_country_code']) == 'GB') {
            return 1;
        }
        return 2;
    }

    /**
     * Get the shipment volume.
     * 
     * @return int
     */
    protected function getTotalVolume()
    {
        $totalVolume = 0;

        foreach ($this->shipment['packages'] as $package) {
            $totalVolume += ($package['length'] / 100) * ($package['width'] / 100) * ($package['height'] / 100);
        }

        return $totalVolume;
    }

    /**
     * Build an array to pass to the label class.
     * 
     * @param SimpleXml object $result
     * @return array
     */
    private function setLabelDataArray($result)
    {
        $transport = 'INT / ';

        if (isUkDomestic($this->shipment['recipient_country_code'])) {
            $transport = 'DOM / ';
        }

        foreach ($result->pieceLabelData as $label) {
            $barcode[] = (string) $label->barcode;
            $weight[] = (string) $label->weightDisplay;
        }

        $hazardous = '';

        if (isset($this->shipment['hazardous']) && ($this->shipment['hazardous'] == 'E' || is_numeric($this->shipment['hazardous']))) {
            $hazardous = 'HAZARDOUS';
        }

        return [
            'consignment_number' => (string) $result->consignmentLabelData->consignmentNumber,
            'transport_display' => $transport . (string) $result->consignmentLabelData->transportDisplay,
            'hazardous' => $hazardous,
            'xray_display' => (string) $result->consignmentLabelData->xrayDisplay,
            'free_circulation_display' => (string) $result->consignmentLabelData->freeCirculationDisplay,
            'sort_split_text' => (string) $result->consignmentLabelData->sortSplitText,
            'weight' => $weight,
            'account_number' => (string) $result->consignmentLabelData->account->accountNumber,
            'cluster_code' => (string) $result->consignmentLabelData->clusterCode,
            'product' => (string) $result->consignmentLabelData->product,
            'option' => (string) $result->consignmentLabelData->option,
            'depot_code' => (string) $result->consignmentLabelData->originDepot->depotCode,
            'collection_date' => (string) $result->consignmentLabelData->collectionDate,
            'transit_depot' => $result->consignmentLabelData->transitDepots->transitDepot,
            'destination_depot_code' => (string) $result->consignmentLabelData->destinationDepot->depotCode,
            'due_day' => (string) $result->consignmentLabelData->destinationDepot->dueDayOfMonth,
            'barcode' => $barcode
        ];
    }

    /**
     * Get TNT postcode for a given town (IE only).
     * 
     * @return type
     */
    protected function getTntPostcode()
    {
        $tntPostcode = new \App\TntPostcode();

        $postcode = $tntPostcode->getPostcode($this->shipment['recipient_country_code'], $this->shipment['recipient_city']);

        if ($postcode) {
            return $postcode;
        }

        return trim($this->shipment['recipient_postcode']);
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
        \App\TransactionLog::create([
            'type' => $type,
            'carrier' => 'tnt',
            'direction' => $direction,
            'msg' => $msg
        ]);
    }

}
