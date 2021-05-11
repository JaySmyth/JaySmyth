<?php

namespace App\CarrierAPI\XDP;

use App\Models\Service;
use App\Models\TransactionLog;
use SimpleXMLElement;

class XDP
{
    private $shipment;
    private $xdp_connect_url = 'https://xdp.sysx.co.uk/api/webservice/rest/endpoint';
    private $xdp_label_url = '';
    private $username;
    private $password;

    public function __construct($shipment, $mode)
    {
        // Data array passed through
        $this->shipment = $shipment;

        // Remove any unwanted characters before generating xml
        $this->removeUnwantedCharactersFromData();

        // Set the correct account number to use
        $this->billShippingAccount = $this->getBillShippingAccount($mode);

        // Get Service Details
        $service = Service::find($this->shipment['service_id']);

        // Define the environment
        switch ($mode) {
            case 'test':
                $this->username = 'XDP123';
                $this->password = '8iu7ewK3';
                break;

            default:
                $this->username = $service->account;
                $this->password = 'VQW58U9H';
                break;
        }

        // Get the Services Carrier Code
        $this->shipment['service'] = $service->carrier_code;
    }

    /**
     * Strips ampersand from values.
     */
    private function removeUnwantedCharactersFromData()
    {
        foreach ($this->shipment as $key => $value) {
            $this->shipment[$key] = str_replace('&', '', $value);
        }

        $this->shipment['recipient_postcode'] = formatUkPostcode($this->shipment['recipient_postcode']);
    }

    /**
     * Get the account number to use.
     *
     * @param  type  $mode
     *
     * @return string
     */
    private function getBillShippingAccount($mode)
    {
        if ($mode == 'test') {
            return '00514432';
        }

        // Provided by the end user
        if (! empty($this->shipment['bill_shipping_account'])) {
            return $this->shipment['bill_shipping_account'];
        }

        // Lookup the account number
        return Service::find($this->shipment['service_id'])->account;
    }

    /**
     * Generates XML and sends it to XDP. Returns an array containing a consignment
     * number and data for generating a label.
     *
     * @return array
     */
    public function sendMessage()
    {
        $reply = [];

        // Generate the XDP Delivery XML
        $xdpDeliveryXml = $this->generateXdpDeliveryXml();

        // Create a transaction log
        $this->log('MSG-1', 'O', $xdpDeliveryXml);

        // Send the XDP Delivery XML to XDP server
        $result = $this->postXdpConnect($xdpDeliveryXml);

        $this->log('REPLY-1', 'I', $result);

        // Obtain the XML portion of the string returned
        if (! $xml = $this->getXmlResult($result)) {
            return $reply['errors'][] = 'Invalid reply from carrier. Please try again.';
        }

        // Read the result into a simpleXML object
        $result = new SimpleXMLElement($xml);

        // Check for errors: move them to our reply array
        if (isset($result->responses->response->errors)) {
            foreach ($result->responses->response->errors as $error) {
                $reply['errors'][] = (string) $error->error;
            }

            return $reply;
        }

        $consignmentNumber = '';
        if (isset($result->responses->response->consignmentno)) {
            $consignmentNumber = (string) $result->responses->response->consignmentno; // Cast the simpleXML object to a string
            for ($i = 1; $i <= $this->shipment['pieces']; $i++) {
                $reply['barcode'][] = $consignmentNumber.str_pad($i, 3, '0', STR_PAD_LEFT);
            }
        }

        if ($consignmentNumber == '') {
            return $reply['errors'][] = 'Unable to obtain a consignment no from carrier.';
        }

        /*
         * Get the URL for the label
         */
        $this->xdp_label_url = (string) $result->responses->response->label;

        // Pass the label data back to use when generating the XDP labels
        $reply['label_data']                 = $this->setLabelDataArray($result->consignment);
        $reply['carrier_consignment_number'] = $consignmentNumber;

        return $reply;
    }

    /**
     * Build XML for express label call.
     *
     * @return string
     */
    private function generateXdpDeliveryXml()
    {
        $prefix  = '<![CDATA[';
        $postfix = ']]>';
        $xml     = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><xdpwebservice></xdpwebservice>');
        $xml->addChild('type', 'create');
        $consignmentNode = $xml->addChild('consignment');
        $consignmentNode->addChild('accountno', $prefix.$this->username.$postfix);
        $consignmentNode->addChild('accesskey', $prefix.$this->password.$postfix);
        $referencesNode = $consignmentNode->addChild('references');
        $referencesNode->addChild('ref', $prefix.$this->shipment['shipment_reference'].$postfix);

        $consignmentNode->addChild('deliverycontact', $prefix.$this->shipment['recipient_name'].$postfix);
        $consignmentNode->addChild('deliverycompany', $prefix.$this->shipment['recipient_company_name'].$postfix);
        $consignmentNode->addChild('deliveryaddress1', $prefix.$this->shipment['recipient_address1'].$postfix);
        $consignmentNode->addChild('deliveryaddress2', $prefix.$this->shipment['recipient_address2'].$postfix);
        $consignmentNode->addChild('deliverytown', $prefix.$this->shipment['recipient_city'].$postfix);
        $consignmentNode->addChild('deliverycounty', $prefix.$this->shipment['recipient_state'].$postfix);
        $consignmentNode->addChild('deliverypostcode', $prefix.$this->shipment['recipient_postcode'].$postfix);
        $consignmentNode->addChild('deliveryphone', $prefix.$this->shipment['recipient_telephone'].$postfix);
        $consignmentNode->addChild('deliveryemail', $prefix.'courier@antrim.ifsgroup.com'.$postfix);
        $consignmentNode->addChild('deliverynotes', $prefix.$this->shipment['special_instructions'].$postfix);

        $consignmentNode->addChild('servicelevel', $prefix.$this->shipment['service'].$postfix);
        $consignmentNode->addChild('manifestpieces', $prefix.$this->shipment['pieces'].$postfix);
        $consignmentNode->addChild('manifestweight', $prefix.$this->shipment['weight'].$postfix);
        $consignmentNode->addChild('insurance', $prefix.'no'.$postfix);
        $goodsDescription = $this->getGoodsDescription();
        $consignmentNode->addChild('insurancegoodsdesc', $prefix.$goodsDescription.$postfix);
        $consignmentNode->addChild('insurancegoodsvalue', $prefix.'0'.$postfix);

        $consignmentNode->addChild('label', $prefix.'yes'.$postfix);
        $dimmensionNode = $consignmentNode->addChild('pieces');
        foreach ($this->shipment['packages'] as $package) {
            $packageNode = $dimmensionNode->addChild('piece');
            $packageNode->addChild('height', $package['height']);
            $packageNode->addChild('width', $package['width']);
            $packageNode->addChild('length', $package['length']);
        }

        return html_entity_decode($xml->asXML());
    }

    /**
     * Get a goods description.
     *
     * @param  type  $packageIndex
     *
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

        if (! empty($this->shipment['contents'])) {
            foreach ($this->shipment['contents'] as $content) {
                return $content['description'];
            }
        }

        return 'COMMODITIES';
    }

    /**
     * Create a transaction log.
     *
     * @param  type  $type
     * @param  type  $direction
     * @param  type  $msg
     */
    protected function log($type, $direction, $msg)
    {
        TransactionLog::create([
            'type'      => $type,
            'carrier'   => 'xdp',
            'direction' => $direction,
            'msg'       => $msg,
        ]);
    }

    /**
     * Send express connect XML to XDP.
     *
     * @param  string  $string
     *
     * @return type
     */
    private function postXdpConnect($string)
    {
        /*
        // $string='<?xml version="1.0" standalone="yes"?>';
        // $string.='<xdpwebservice><consignment><accountno><![CDATA["XDP123"]]></accountno><accesskey><![CDATA["1b4D7if8"]]></accesskey><references><ref><![CDATA["test"]]></ref></references><deliverycontact><![CDATA["MR Joe Bloggs"]]></deliverycontact><deliverycompany><![CDATA["XDP EXPRESS"]]></deliverycompany><deliveryaddress1><![CDATA["FAIRVIEW IND EST"]]></deliveryaddress1><deliveryaddress2><![CDATA["KINGSBURY ROAD"]]></deliveryaddress2><deliverytown><![CDATA["CURDWORTH"]]></// // deliverytown><deliverycounty><![CDATA["BIRMINGHAM"]]></deliverycounty><deliverypostcode><![CDATA["B76 9EE"]]></deliverypostcode><deliveryphone><![CDATA["01675 471498"]]></deliveryphone><deliveryemail><![CDATA["technical@XDP.CO.UK"]]></deliveryemail><deliverynotes><![CDATA[""]]></deliverynotes><servicelevel><![CDATA["O/N"]]></servicelevel><manifestpieces><![CDATA["2"]]></manifestpieces><manifestweight><![CDATA["20"]]></manifestweight><insurance><![CDATA["no"]]></insurance><insurancegoodsdesc><![CDATA[""]]></insurancegoodsdesc><insurancegoodsvalue><![CDATA[""]]></insurancegoodsvalue><label><![CDATA["yes"]]></label><pieces><piece><height><![CDATA["111"]]></height><width><![CDATA["222"]]></width><length><![CDATA["333"]]></length></piece><piece><height><![CDATA["111"]]></height><width><![CDATA["222"]]></width><length><![CDATA["333"]]></length></piece></pieces></consignment></xdpwebservice>';
        */

        $method = 'POST';
        $header = [
            'Accept: */*',
            'Content-type: application/x-www-form-urlencoded',
            'Content-length: '.strlen($string),
        ];

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $this->xdp_connect_url);                // set url to post to
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if (! empty($header)) {
            curl_setopt($ch, CURLOPT_HEADER, 1);            // CURL to output header
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  // Header for CURL to output
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);            // CURL NOT to output header
        }

        curl_setopt($ch, CURLOPT_POST, 0);                  // Transmit as POST method
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $result = curl_exec($ch);                           // send!
        curl_close($ch);                                    // close

        return $result;
    }

    /**
     * Get the result from the XDP Express Connect response.
     *
     * @param  type  $response
     *
     * @return bool|string
     */
    private function getXmlResult($response)
    {
        $sep      = chr(10).chr(13);
        $elements = explode($sep, $response);

        foreach ($elements as $element) {
            if (strpos($element, '<xdpwebservice>') !== false) {
                return trim($element);
            }
        }

        return;
    }

    /**
     * Build an array to pass to the label class.
     *
     * @param  SimpleXml object $result
     *
     * @return array
     */
    private function setLabelDataArray($result)
    {
        // Retrieve Label data. Retry every 300ms max 20 times if unsuccessful
        $url = $this->xdp_label_url;
        return retry(20, function ($result) use ($url) {
            return base64_encode(file_get_contents($url));
        }, 300);

        // return base64_encode(file_get_contents($this->xdp_label_url));
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
     * Count the number of items in a package.
     *
     * @param  type  $packageIndex
     *
     * @return type
     */
    private function getPackageItemCount($packageIndex)
    {
        $items = 0;

        if (isset($this->shipment['contents']) && is_array($this->shipment['contents'])) {
            foreach ($this->shipment['contents'] as $content) {
                $items++;
            }
        }

        if ($items <= 0) {
            return 1;
        }

        return $items;
    }

    /**
     * Send express label XML to XDP.
     *
     * @param  type  $string
     *
     * @return type
     */
    private function postXdpLabel($string)
    {
        $ch = curl_init(); // New curl instance
        curl_setopt($ch, CURLOPT_URL, $this->xdp_label_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ((! empty(trim($this->username))) && (! empty(trim($this->password)))) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);

        $is_secure = strpos($this->xdp_label_url, 'https://');
        if ($is_secure === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
