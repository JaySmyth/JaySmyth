<?php

namespace App\Http\Controllers;

use App\CarrierAPI\APIResponse;
use App\CarrierAPI\CarierAPI;
use App\Models\Company;
use App\Models\ImportConfig;
use App\Models\Shopify;
use App\Models\ShopifyAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShopifyController extends Controller
{
    protected $domain;
    protected $headers;
    protected $topic;
    protected $data;
    protected $shopify;
    protected $hmacSha256;
    protected $rawData;
    protected $ship;
    protected $account;
    protected $user;
    protected $company;
    protected $preferences;
    protected $defaults;
    protected $alertRecipients;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->httpResponse = new APIResponse();
        $this->alertRecipients = ['sender', 'recipient', 'broker', 'other'];
        $this->defaults = [
            'ship_reason' => 'SOLD',
            'invoice_type' => 'c',
            'terms_of_sale' => 'DAP',
            'bill_shipping' => 'sender',
            'bill_tax_duty' => 'recipient',
            'weight_uom' => 'KG',
            'dimension_uom' => 'CM',
            'ship_reason' => 'SOLD',
            'ship_reason' => 'SOLD',
        ];
        foreach ($this->alertRecipients as $recipient) {
            $this->defaults['alerts.'.$recipient.'.despatched'] = 'N';
            $this->defaults['alerts.'.$recipient.'.out_for_delivery'] = 'N';
            $this->defaults['alerts.'.$recipient.'.delivered'] = 'N';
            $this->defaults['alerts.'.$recipient.'.cancelled'] = 'N';
            $this->defaults['alerts.'.$recipient.'.problems'] = 'N';
        }
    }

    public function shopifyRequest(Request $request)
    {
        $this->decodeInput($request);

        if (! empty($this->rawData)) {
            $this->shopify = new Shopify($this->domain);
            if ($this->shopify->verify_webhook($this->domain, $this->rawData, $this->hmacSha256)) {
                if (! empty($this->data)) {
                    return $this->processTransaction();
                    dd($this->ship);
                } else {
                    return $this->httpResponse->respondInvalid('Invalid JSON Data');
                }
            } else {
                return $this->httpResponse->respondUnAuthorized('UnAuthorized Access');
            }
        }
    }

    protected function processTransaction()
    {
        switch ($this->topic) {
            case 'orders/create':
                $this->preProcessOrder();
                return $this->processOrder();
            break;
            case 'fulfillment/create':
                $this->preProcessFulfillment();
                return $this->processFulfillment();
            break;
        }

        return $this->httpResponse->respondInvalid('Unknown Transaction type');
    }

    protected function decodeInput(Request $request)
    {
        $this->rawData = file_get_contents('php://input');
        $this->domain = $request->header('x-shopify-shop-domain', '');
        $this->hmacSha256 = $request->header('x-shopify-hmac-sha256', '');
        $this->topic = $request->header('X-Shopify-Topic', '');
        $this->headers = $request->header();
        $this->data = $request->all();
        // logData('shop_hd', json_encode($this->headers));
        // logData('shop_in', $this->rawData);
    }

    protected function preProcessOrder()
    {
        $this->shopify = new Shopify($this->domain);
        $this->shopify->setOrderId($this->data['id']);
        $this->account = ShopifyAccount::where('domain', $this->domain)->first();
        $this->user = User::find($this->account->user_id);
        $this->company = Company::find($this->account->company_id);
        $this->importConfig = ImportConfig::where('company_id', $this->company->id)
            ->where('user_id', $this->user->id)
            ->where('mode_id', '1')
            ->first();

        // Build User preferences in case we need them later
        $this->buildUserPreferences();
    }

    protected function buildUserPreferences()
    {

        // Build user Preferences for this company
        $this->preferences = [];
        $preferences = $this->user->preferences;
        foreach ($preferences as $preference) {
            if ($preference['company_id'] == $this->company->id) {
                $this->preferences[$preference['field']] = $preference['value'];
            }
        }

        // Add Company Import Config preferences
        $this->setImportConfigDefault('service', 'default_service', '');
        $this->setImportConfigDefault('terms', 'default_terms', 'DAP');
        $this->setImportConfigDefault('pieces', 'default_pieces', '1');
        $this->setImportConfigDefault('goods_description', 'default_goods_description', '');
        $this->setImportConfigDefault('recipient_telephone', 'default_recipient_telephone', '');
        $this->setImportConfigDefault('recipient_email', 'default_recipient_email', '');
        $this->setImportConfigDefault('weight', 'default_weight', '');
        $this->setImportConfigDefault('customs_value', 'default_customs_value', '');
    }

    protected function setImportConfigDefault($subject, $configSubject, $defaultValue)
    {
        if (! isset($this->preference[$subject])) {
            if (isset($this->importConfig[$configSubject]) && ! empty($this->importConfig[$configSubject])) {
                $this->preference[$subject] = $this->importConfig[$configSubject];
            } else {
                if (! empty($defaultValue)) {
                    $this->preference[$subject] = $defaultValue;
                }
            }
        }
    }

    protected function processOrder()
    {
        $this->buildShipment();
        dd($this->ship);
        if (! empty($this->ship)) {
            $response = $this->createShipment();
            // logData('shop_or', 'Order: '.$order->id.'/ '.$this->data['id'].' created');
            return $this->httpResponse->respondSuccess('Order Processed');
        } else {
            $msg = 'Unable to create Order: '.$this->data['id'].'<br>';
            $msg .= 'Data:<br>'.json_encode($this->data).'<br><br>';

            dd($msg);
            Mail::to(['garfield@lifepass.eu'])->send(new ErrorException($msg));
            return $this->httpResponse->respondInvalid('Unable to process Order');
        }
    }

    protected function buildShipment()
    {
        $this->addSummary();
        $this->addShipper();
        $this->addRecipient();
        $this->addPackages();
        // $this->addHazardous();
        // $this->addAlcohol();
        $this->addCommodities();
        // $this->addBroker();
        // $this->addOther();
        $this->addAlerts();
        // $this->addLabelSpec();
    }

    protected function getUserPreference($subject)
    {
        // If set return User preference
        if (isset($this->preferences[$subject])) {
            return $this->preferences[$subject];
        }
        
        /*
         * if mv eg. contents.3.description is not set
         * check base value eg. contents.0.description
         */
        $start = stripos($subject, '.');
        if ($start == true) {
            $end = strpos($subject, '.', $start);
            if ($end == true) {
                $subject = substr_replace($subject, '0', $start+1, $end-$start+1);
                if (isset($this->preferences[$subject])) {
                    return $this->preferences[$subject];
                }
            }
        }

        // Get IFS System Default preferences
        if (isset($this->defaults[$subject])) {
            return $this->defaults[$subject];
        }
    }

    protected function addSummary()
    {
        $this->ship["company_id"] = $this->company->id;
        $this->ship["company_code"] = $this->company->company_code;
        $this->ship["collection_date"] = date('Y-m-d');
        $this->ship["pieces"] = count($this->data['line_items']) ?? 1;
        $this->ship["shipment_reference"] = $this->data['order_number'];
        $this->ship["ultimate_destination_country_code"] = $this->data['shipping_address']['country_code'];
        $this->ship["ship_reason"] = $this->getUserPreference('ship_reason');
        $this->ship["special_instructions"] = $this->getUserPreference('special_instructions');
        $this->ship["invoice_type"] = $this->getUserPreference('invoice_type');
        // $this->ship["insurance_value"] = "0";
        $this->ship["commercial_invoice_comments"] = $this->data['line_items'][0]['title'] ?? $this->getUserPreference('goods_description');
        $this->ship["goods_description"] = $this->data['line_items'][0]['title'] ?? $this->getUserPreference('goods_description');
        $this->ship["documents_description"] = $this->data['line_items'][0]['title'] ?? $this->getUserPreference('goods_description');
        $this->ship["special_instructions"] = $this->getUserPreference('special_instructions');
        $this->ship['lithium_batteries'] =  '';
        $this->ship["terms_of_sale"] = $this->getUserPreference('terms');
        $this->ship["bill_shipping"] = $this->getUserPreference('bill_shipping');
        $this->ship["bill_tax_duty"] = $this->getUserPreference('bill_tax_duty');
        $this->ship["bill_shipping_account"] = "";
        $this->ship["bill_tax_duty_account"] = "";

        // * ****** Not currently required ***********
        // $this->ship["service_code"] = $this->data['shipping_lines']['requested_fulfillment_service_id'] ?? '';
        // $this->ship["weight"] = 20;
        // $this->ship["volumetric_weight"] = 20;
        // $this->ship["weight_uom"] = "KG";
        // $this->ship["dimension_uom"] = "CM";
        // $this->ship["country_of_destination"] = "GB";
        // $this->ship["customs_value"] = "200";
        // $this->ship["currency_code"] = $this->data['currency'];
        // $this->ship["documents_flag"] = "N";
    }

    /*
     * Retrieves the Company using the Company Code
     * Stores the company in $this->company
     * and returns the company id
     */
    protected function getCompany($companyCode)
    {
        $this->company = Company::where('company_code', $companyCode)->first();
        if (isset($this->company->id)) {
            return $this->company->id;
        }
    }

    protected function addShipper()
    {
        $this->ship["sender_name"] = $this->user->name;
        $this->ship["sender_company_name"] = $this->company->company_name;
        $this->ship["sender_address1"] = $this->company->address1;
        $this->ship["sender_address2"] = $this->company->address2;
        $this->ship["sender_address3"] = $this->company->address3;
        $this->ship["sender_city"] = $this->company->city;
        $this->ship["sender_country_code"] = $this->company->country_code;
        $this->ship["sender_state"] = $this->company->state;
        $this->ship["sender_postcode"] = $this->company->postcode;
        $this->ship["sender_telephone"] = $this->company->telephone;
        $this->ship["sender_email"] = $this->company->email;
        $this->ship["sender_type"] = $this->getShipperType();
    }

    protected function addRecipient()
    {
        $state = $this->data['shipping_address']['provence'] ?? '';
        if ($this->data['shipping_address']['country_code'] == 'US') {
            if (isset($this->data['shipping_address']['provence_code'])) {
                $state = $this->data['shipping_address']['provence_code'];
            }
        }
        $this->ship["recipient_name"] = $this->data['shipping_address']['first_name'] . ' ' . $this->data['shipping_address']['last_name'];
        $this->ship["recipient_company_name"] = $this->data['shipping_address']['company'] ?? '';
        $this->ship["recipient_address1"] = $this->data['shipping_address']['address1'] ?? '';
        $this->ship["recipient_address2"] = $this->data['shipping_address']['address2'] ?? '';
        // $this->ship["recipient_address3"] = $this->data['shipping_address']['company'];
        $this->ship["recipient_city"] = $this->data['shipping_address']['city'] ?? '';
        $this->ship["recipient_country_code"] = $this->data['shipping_address']['country_code'] ?? '';
        $this->ship["recipient_state"] = $state ?? '';
        $this->ship["recipient_postcode"] = $this->data['shipping_address']['zip'] ?? '';
        $this->ship["recipient_telephone"] = $this->data['shipping_address']['phone'] ?? '';
        $this->ship["recipient_email"] = $this->data['email'] ?? '';
        // $this->ship["recipient_account_number"] = "";
        $this->ship["recipient_type"] = $this->getRecipientType();
        $this->ship["display_recipient_email"] = $this->data['shipping_address']['company'] ?? '';
    }

    protected function getRecipientType()
    {
        if (! empty($this->company->recipient_type_override)) {
            return $this->company->recipient_type_override;
        }
        if (empty($this->data['shipping_address']['company'])) {
            return 'R';
        } else {
            return 'C';
        }
    }

    protected function getShipperType()
    {
        if (! empty($this->company->shipper_type_override)) {
            return $this->company->shipper_type_override;
        }
        if (empty($this->data['shipping_address']['company'])) {
            return 'R';
        } else {
            return 'C';
        }
    }

    protected function addPackages()
    {
        $cnt = 0;
        foreach ($this->data['line_items'] as $item) {
            $this->ship['packages.'.$cnt.'.packaging_code'] = $this->getUserPreference('packages.'.$cnt.'.packaging_code');
            $this->ship['packages.'.$cnt.'.weight'] = $this->getUserPreference('packages.'.$cnt.'.width');
            $this->ship['packages.'.$cnt.'.length'] = $this->getUserPreference('packages.'.$cnt.'.length');
            $this->ship['packages.'.$cnt.'.width'] = $this->getUserPreference('packages.'.$cnt.'.width');
            $this->ship['packages.'.$cnt.'.height'] = $this->getUserPreference('packages.'.$cnt.'.height');
            $this->ship['packages.'.$cnt.'.dry_ice_weight'] = $this->getUserPreference('dry_ice_weight');
            $cnt++;
        }
    }

    // * ************* Not Used ******************
    protected function addHazardous()
    {
        $this->ship["hazardous"] = "N";
        /*
        $this->ship["flag"] = "E";
        $this->ship["class"] = "";
        $this->ship["excepted_qty"] = "Y";
        */
    }

    // * ************* Not Used ******************
    protected function addAlcohol()
    {
        $this->ship["alcohol.type"] = "";
        /*
        $this->ship["alcohol.packaging"] = "Bottle";
        $this->ship["alcohol.volume"] = ".5";
        $this->ship["alcohol.quantity"] = "2";
        */
    }

    protected function addCommodities()
    {
        $cnt = 0;
        $commodities=[];
        foreach ($this->data['line_items'] as $item) {
            $this->ship['contents.'.$cnt.'.id'] = $cnt+1;
            $this->ship['contents.'.$cnt.'.description'] = $item['name'] ?? $this->getUserPreference('contents.'.$cnt.'.description');
            $this->ship['contents.'.$cnt.'.product_code'] = $item['product_id'] ?? $this->getUserPreference('contents.'.$cnt.'.product_code');
            $this->ship['contents.'.$cnt.'.currency_code'] = $item['duties']['shop_money']['currency_code'] ?? $this->getUserPreference('contents.'.$cnt.'.currency_code');
            $this->ship['contents.'.$cnt.'.country_of_manufacture'] = $item['duties']['country_code_of_origin'] ?? $this->getUserPreference('contents.'.$cnt.'.country_of_manufacture');
            $this->ship['contents.'.$cnt.'.manufacturer'] = $item['duties']['manufacturer'] ?? $this->getUserPreference('contents.'.$cnt.'.manufacturer');
            $this->ship['contents.'.$cnt.'.uom'] = $item['duties']['shop_money']['currency_code'] ?? $this->getUserPreference('contents.'.$cnt.'.uom');
            // $this->ship['contents.'.$cnt.'.commodity_code'] = $item['duties']['shop_money']['currency_code'];
            $this->ship['contents.'.$cnt.'.harmonized_code'] = $item['duties']['harmonized_system_code'] ?? $this->getUserPreference('contents.'.$cnt.'.harmonized_code');
            $this->ship['contents.'.$cnt.'.shipping_cost'] = $item['duties']['shop_money']['amount'] ?? $this->getUserPreference('contents.'.$cnt.'.shipping_cost');
            $this->ship['contents.'.$cnt.'.quantity'] = $item['quantity'] ?? $this->getUserPreference('contents.'.$cnt.'.quantity');
            $this->ship['contents.'.$cnt.'.unit_weight'] = ($item['grams'] + 500)/1000 ?? $this->getUserPreference('contents.'.$cnt.'.unit_weight');
            $this->ship['contents.'.$cnt.'.unit_value'] = $item['price'] ?? $this->getUserPreference('contents.'.$cnt.'.unit_value');
            $cnt++;
        }
        $this->ship["weight_uom"] = "KG";
        $this->ship["commodity_count"] = $cnt;
        $this->ship["eori"] = $this->preferences['eori'] ?? '';
        // $this->ship["export_license"] = "EX1000012";
        // $this->ship["export_license_date"] = "2016-11-25";
    }

    protected function addBroker()
    {
        // $this->ship["identifier"] = "Garfield McBroom";
        $this->ship["broker.contact"] = "Garfield McBroom";
        $this->ship["broker.company"] = "IFS Global Logistics Ltd";
        $this->ship["broker.address1"] = "IFS Logistics Park";
        $this->ship["broker.address2"] = "IFS Business Park";
        $this->ship["broker.city"] = "Muckamore";
        $this->ship["broker.country_code"] = "GB";
        $this->ship["broker.state"] = "Antrim";
        $this->ship["broker.postcode"] = "BT41 4QE";
        $this->ship["broker.telephone"] = "02894464211";
        $this->ship["broker.email"] = "gmcb@antrim.ifsgroup.com";
        $this->ship["broker.account"] = "";
        $this->ship["broker.id"] = "12345678";
        $this->ship["display.broker.email"] = $this->ship["broker.email"];
    }

    // * ************* Not Used ******************
    protected function addOther()
    {
        $this->ship["identifier"] = "John McBroom";
        $this->ship["contact"] = "John McBroom";
        $this->ship["company_name"] = "IFS Global Logistics Ltd";
        $this->ship["telephone"] = "02894464211";
        $this->ship["email"] = "gmcb@antrim.ifsgroup.com";
        $this->ship["address1"] = "IFS Logistics Park";
        $this->ship["address2"] = "IFS Business Park";
        $this->ship["address3"] = "Seven Mile Straight";
        $this->ship["city"] = "Muckamore";
        $this->ship["state"] = "Antrim";
        $this->ship["postcode"] = "BT41 4QE";
        $this->ship["country_code"] = "GB";
        $this->ship["account"] = "";
        $this->ship["type"] = "C";
        $this->ship["other_email"] = $this->ship["email"];
    }

    protected function addAlerts()
    {
        foreach ($this->alertRecipients as $recipient) {
            $this->setPreference('alerts.'.$recipient.'.despatched');
            $this->setPreference('alerts.'.$recipient.'.out_for_delivery');
            $this->setPreference('alerts.'.$recipient.'.delivered');
            $this->setPreference('alerts.'.$recipient.'.cancelled');
            $this->setPreference('alerts.'.$recipient.'.problems');
        }
    }

    protected function setPreference($alertType)
    {
        if (isset($this->preferences[$alertType])) {
            $this->ship[$alertType] = $this->preferences[$alertType];
        }
    }

    protected function addLabelSpec()
    {
        $this->ship["label_specification"] = [
            "image_type" => "PDF",
            "label_size" => "6X4"
        ];
    }
}
