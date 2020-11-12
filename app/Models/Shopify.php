<?php

namespace App\Models;

use \App\Models\ShopifyAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class Shopify extends Model
{
    protected $domain;
    protected $account;
    protected $orderId;
    protected $urls;

    public function __construct($domain = '')
    {
        $this->domain = $domain;
        $this->account = ShopifyAccount::where('domain', $domain)->first();
        $this->urls = [];
    }

    /*
     * Set Domain and clear Order URLs
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        $this->urls = [];
    }

    /*
     * Set OrderId and create Order URLs
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        $this->urls = [
            'checkOrder' => 'https://'.$this->domain.'/admin/orders/'.$orderId,
            'checkOrderRisk' => 'https://'.$this->domain.'/admin/orders/'.$orderId.'/risks.json',
            'fullfillment' => 'https://'.$this->domain.'/admin/orders/'.$orderId.'/fulfillments.json',
        ];
    }

    /*
     * ************* Not Used ******************
     * Set Message to Shopify
     *   Could be used to signify Fulfillment
     * *****************************************
     */
    public function send($msgType)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->urls[$msgType]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->account->username.':'.$this->account->password);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /*
     * ************* Not Used ******************
     * Get Shopifys Recomendation for this Order
     * *****************************************
     */
    protected function getRecommendation($arrayResult)
    {
        $doneChecking = false;
        $doneMessage = false;
        $result = ['recommendation' => 'accept', 'message' => ''];
        foreach ($arrayResult->risks as $riskKey => $riskData) {
            foreach ($riskData as $key => $data) {
                if ($key === 'recommendation') {
                    if ($doneChecking === false) {
                        if (($data) === ('cancel') || ($data) === ('investigate')) {
                            $result['recommendation'] = $data;
                            $doneChecking = true;
                        }
                    }
                }
                if ($key === 'message') {
                    if ($doneMessage === false) {
                        if (($data) === ('cancel') || ($data) === ('investigate')) {
                            $result['message'] = $data;
                            $doneMessage = true;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /*
     * ************* Not Used ******************
     * If Shopify has flagged as potential fraud
     * Notify someone
     * *****************************************
     */
    protected function notifyIfFraud($result)
    {
        $recommendation = $result['recommendation'];
        $message = $result['message'];
        if (($recommendation === 'cancel') || ($recommendation === 'investigate')) {

            // Create the Order Link
            $htmlLink = '<a href="'.$this->urls['checkOrder'].'">Please make sure you are logged in and then click here for the Order</a>';

            Mail::to($this->emails)->send(new \App\Mail\orderFraud($this->orderId, $htmlLink, $recommendation));
        }
    }

    /*
     * Verify that the Webhook is from the stated sender
     */
    public function verify_webhook($domain, $data, $hmacHeader)
    {
        $calculated_hmac = '';
        if (isset($this->account->secret)) {
            $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $this->account->secret, true));
        }

        return hash_equals($hmacHeader, $calculated_hmac);
    }

    /*
     * ************* Not Used ******************
     * Build a Shopify Fulfillment transaction
     * *****************************************
     */
    public function buildFulfillment()
    {
        $post_data = (object) [
            'fulfillment' => array(
                'location_id' => $location_id,
                'tracking_number' => 'Electro',
                'tracking_company' => 'LifePass'
            ),
            'notify_customer' => 0
        ];
    }
}
