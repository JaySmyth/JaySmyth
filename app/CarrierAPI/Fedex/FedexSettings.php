<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\CarrierAPI\Fedex;

use App\Models\Carrier;
use App\Models\Service;
use App\Models\CarrierPackagingType;

/**
 * Description of FedexFields.
 *
 * @author gmcbroom
 */
class FedexSettings
{
    public $fieldDefs = [];
    public $options = [];
    public $hazardFlags = [];
    public $mvfields = [];
    public $addressType = [];
    public $carrier = [];
    public $svc = [];
    public $packageTypes = [];
    public $weightUnits = [];
    public $shortWeightUnits = [];
    public $dimensionUnits = [];
    public $shortDimensionUnits = [];
    public $payor = [];
    public $paymentType = [];
    public $terms = [];
    public $labelDefn = [];
    public $fldno = [];
    public $group = [];
    public $mult = [];
    public $lbMult = [];
    public $labelStockType;
    public $logo;

    public function __construct($depotId = 1)
    {

        // Supported Options
        $this->options = ['CARGO', 'AM', '9:00', '10:00', '12:00'];

        // Hazard Flags
        $this->hazardFlags = ['E' => 'E', '1' => 'A', '2' => 'A', '3' => 'A', '4' => 'A', '5' => 'A', '6' => 'A', '7' => 'I', '8' => 'A', '9' => 'I'];

        // Multivalue fields
        $this->mvfields = ['29', '65', '664', '3063', '3064'];

        // Address Type - Used for Residential Delivery Y/N
        $this->addressType = ['C' => 'N', 'R' => 'Y', 'c' => 'N', 'r' => 'Y'];

        // Label Stock Sizes
        $this->labelStockType['PDF']['6X4'] = '46P';
        $this->labelStockType['PNG']['6X4'] = '46L';

        // Get my Carrier Details
        $this->carrier = Carrier::where('code', 'fedex')->first();

        // Define available services
        $services = Service::where('carrier_id', $this->carrier->id)->where('depot_id', $depotId)->get();

        foreach ($services as $svc) {
            $this->svc[$svc->code] = $svc->carrier_code;
        }

        // Define Fedex Package types supported
        $packages = CarrierPackagingType::where('carrier_id', $this->carrier->id)->get()->implode('code', ',');
        $this->packageTypes = explode(',', $packages);

        // Define Weight Units
        $this->weightUnits = ['kg' => 'KGS', 'lb' => 'LBS'];

        // Define Short Weight Units for Send Shipment transaction
        $this->shortWeightUnits = ['kg' => 'KGS', 'lb' => 'LBS'];

        // Define DimensionUnit Units
        $this->dimensionUnits = ['cm' => 'CM', 'in' => 'IN'];

        // Define Short DimensionUnit Units for Send Shipment transaction
        $this->shortDimensionUnits = ['cm' => 'C', 'in' => 'I'];

        // Define Payor Codes
        $this->payor = ['sender' => 'SP', 'recipient' => 'RP', 'other' => 'SO'];

        // Define Payor Codes for Duty
        $this->paymentType = ['sender' => '1', 'recipient' => '2', 'other' => '3'];

        // Define Terms of Sale
        $this->terms = ['fca' => '1', 'cip' => '2', 'cpt' => '3', 'exw' => '4', 'ddp' => '6', 'dat' => '7', 'dap' => '8'];

        // Define supported Label Sizes
        $this->labelDefn = [
            'A4' => ['p', 'in', 'A4', true, 'UTF-8', false],
            '6X4' => ['p', 'in', [6, 4], true, 'UTF-8', false],
        ];

        // Define Logo
        $this->logo = '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';

        //##################################################
        //#
        //#  Define Known fields for Transaction 020
        //#  Format is:
        //#
        //#     FieldNo/Classification/Multiplier
        //#
        //#     FieldNo : The Field no Fedex use to identify the field
        //#     Classification : DryIce, Dangerous goods etc.
        //#     Multiplier : 0 - no multiplier, 100 - multiply by 100
        //#
        //# Sample UK Transaction
        //#
        //# 0,"020"4,"IFS Global Logistics"32,"Daryl Shannon"5,"IFS Logistics Park"6,"Seven Mile Straight"7,"Antrim"8,"Antrim"117,"GB"9,"BT41 4QE"183,"02894464211"10,"205691588"12,"caroline murphy"13,"118 rickmansworth road"15,"watford"16,"herts"50,"GB"17,"WD18 7JG"18,"07852126201"1174,"N"1331,"N"24,"20160211"25,"rrererer"1086,"10"1274,"26"72,"SP"1090,"UKL"74,"GB"75,"KGS"112,"20"116,"2"1273,"01"23,"1"43,"0"79-1,"errererer"57-1,"1"58-1,"1"59-1,"1"1670-1,"100"99,""
        //#
        //##################################################

        $this->fieldDefs[] = 'TxType/0/RESPONSE/0/0'; // ??
        $this->fieldDefs[] = 'transaction_id/1/GENERAL/0/0';
        $this->fieldDefs[] = 'Mawbs/29/RESPONSE/0/0'; // ??
        $this->fieldDefs[] = 'URSA/30/RESPONSE/0/0'; // ??
        $this->fieldDefs[] = 'commit_code/33/RESPONSE/0/0'; // ??
        $this->fieldDefs[] = 'Barcode_1D/65/RESPONSE/0/0';
        $this->fieldDefs[] = 'Barcode_1D/664/RESPONSE/0/0';
        $this->fieldDefs[] = 'station_id/195/RESPONSE/0/0';
        $this->fieldDefs[] = 'location_id/198/RESPONSE/0/0';
        $this->fieldDefs[] = 'tracking_form_id/526/RESPONSE/0/0';
        $this->fieldDefs[] = 'master_form_id/1124/RESPONSE/0/0';
        $this->fieldDefs[] = 'ursa_prefix/1136/RESPONSE/0/0';
        $this->fieldDefs[] = 'MeterNumber/498/RESPONSE/0/0';
        $this->fieldDefs[] = 'Pre_Assign_Flag/1221/GENERAL/0/0';
        $this->fieldDefs[] = 'Pre_Assign_TxIDs/1222/GENERAL/0/0'; // NOT IN USE YET
        $this->fieldDefs[] = 'OriginID1/1084/GENERAL/0/0';
        $this->fieldDefs[] = 'TxID/1123/GENERAL/0/0'; // ??
        $this->fieldDefs[] = 'IRS_EIN_EORI/1139/IRSEINEORI/0/0';
        $this->fieldDefs[] = 'sender_id_type/1352/MANUAL/0/0';
        $this->fieldDefs[] = 'custom_label_flag/1660/MANUAL/0/0';
        $this->fieldDefs[] = 'Barcode_2D/3064/RESPONSE/0/0'; // ??
        $this->fieldDefs[] = 'signature_required/2399/GENERAL/0/0';

        //
        // Shipper Details
        $this->fieldDefs[] = 'sender_company_name/4/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_name/32/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_address1/5/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_address2/6/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_address3/2420/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_city/7/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_state_code/8/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_country_code/117/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_postcode/9/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_telephone/183/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_email/1201/GENERAL/0/0';
        $this->fieldDefs[] = 'sender_account/10/GENERAL/0/0';
        //
        //Receiver Details
        $this->fieldDefs[] = 'recipient_company_name/11/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_name/12/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_address1/13/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_address2/14/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_city/15/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_state_code/16/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_country_code/50/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_postcode/17/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_telephone/18/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_email/1202/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_type/440/GENERAL/0/0';
        $this->fieldDefs[] = 'recipient_account/177/GENERAL/0/0';
        //
        // Shipment Level Details
        $this->fieldDefs[] = 'collection_date/24/SHIPMENT/0/0';
        $this->fieldDefs[] = 'shipment_reference/25/SHIPMENT/0/0';
        $this->fieldDefs[] = 'special_instructions/3021/SHIPMENT/0/0';
        $this->fieldDefs[] = 'service_code/1274/SHIPMENT/0/0';
        $this->fieldDefs[] = 'pieces/116/SHIPMENT/0/0';
        $this->fieldDefs[] = 'adm_type/1958/ADM/0/0';                             // Admissibility Package Type - if using own packaging Always set to BOX
        $this->fieldDefs[] = 'weight_uom/75/SHIPMENT/0/0';
        $this->fieldDefs[] = 'dims_uom/1116/SHIPMENT/0/0';                        // Need to convert units to Fedex notation
        $this->fieldDefs[] = 'weight/112/SHIPMENT/10/0';
        $this->fieldDefs[] = 'volumetric_weight/1086/SHIPMENT/10/0';
        $this->fieldDefs[] = 'country_of_destination/74/SHIPMENT/0/0';
        $this->fieldDefs[] = 'bill_shipping/23/SHIPMENT/0/0';
        $this->fieldDefs[] = 'bill_shipping_account/20/SHIPMENT/0/0';
        $this->fieldDefs[] = 'bill_shipping_to_country/1195/MANUAL/0/0';          // Output triggered by bill_shipping
        $this->fieldDefs[] = 'bill_tax_duty/70/SHIPMENT/0/0';                     // Output triggered by bill_shipping
        $this->fieldDefs[] = 'bill_tax_duty_account/71/SHIPMENT/0/0';             // Output triggered by bill_shipping
        $this->fieldDefs[] = 'bill_tax_duty_to_country/1032/MANUAL/0/0';          // Output triggered by bill_shipping
        $this->fieldDefs[] = 'terms_of_sale/72/SHIPMENT/0/0';
        $this->fieldDefs[] = 'customs_value_currency_code/68/SHIPMENT/0/0';
        $this->fieldDefs[] = 'customs_value/119/SHIPMENT/100/100';

        $this->fieldDefs[] = 'insurance_value/69/SHIPMENT/100/100';
        $this->fieldDefs[] = 'insurance_currency/1090/MANUAL/0/0';

        // Package Level data
        $this->fieldDefs[] = 'packages.*.sequence_number/1117/MANUAL/0/0';        // Output triggered by packages.*.weight
        $this->fieldDefs[] = 'packages.*.weight/1670/PACKAGE/100/100';
        $this->fieldDefs[] = 'packages.*.length/59/MANUAL/0/0';                   // Output triggered by packages.*.weight
        $this->fieldDefs[] = 'packages.*.width/58/MANUAL/0/0';                    // Output triggered by packages.*.weight
        $this->fieldDefs[] = 'packages.*.height/57/MANUAL/0/0';                   // Output triggered by packages.*.weight
        $this->fieldDefs[] = 'packages.*.dry_ice_weight/1684/MANUAL/100/100';       // Output triggered by packages.*.weight
        $this->fieldDefs[] = 'packages.*.packaging_code/1273/MANUAL/0/0';         // Output triggered by packages.*.weight
        // DryIce
        $this->fieldDefs[] = 'dry_ice_flag/1268/MANUAL/0/0';                      // Output triggered by packages.*.weight

        $this->fieldDefs[] = 'lithium_batteries/7801/SHIPMENT/0/0';

        // Alcohol
        $this->fieldDefs[] = 'alcohol.quantity/52/ALCOHOL/0/0';
        $this->fieldDefs[] = 'alcohol.flag/1332/ALCOHOL/0/0';                     // Option triggered by alcohol.quantity
        $this->fieldDefs[] = 'alcohol.type/40/ALCOHOL/0/0';                       // Option triggered by alcohol.quantity
        $this->fieldDefs[] = 'alcohol.packaging/41/ALCOHOL/0/0';                  // Option triggered by alcohol.quantity
        $this->fieldDefs[] = 'alcohol.volume/42/ALCOHOL/0/0';                     // Option triggered by alcohol.quantity
        //
        // $this->fieldDefs[] = 'CustomsClearanceDetail.FreeCirculation/1097/OPTION/0/0";
        //
        // Options - Details in Options function
        $this->fieldDefs[] = 'special_services/0/OPTION/0/0';
        //
        // Broker Fields
        $this->fieldDefs[] = 'broker_select/1174/SHIPMENT/0/0';                   // Set this at shipment level
        $this->fieldDefs[] = 'broker.contact/66/BROKER/0/0';
        $this->fieldDefs[] = 'broker.company/1180/BROKER/0/0';
        $this->fieldDefs[] = 'broker.telephone/67/BROKER/0/0';
        $this->fieldDefs[] = 'broker.email/1343/BROKER/0/0';
        $this->fieldDefs[] = 'broker.address1/1181/BROKER/0/0';
        $this->fieldDefs[] = 'broker.address2/1182/BROKER/0/0';
        $this->fieldDefs[] = 'broker.city/1183/BROKER/0/0';
        $this->fieldDefs[] = 'broker.state/1184/BROKER/0/0';
        $this->fieldDefs[] = 'broker.postcode/1185/BROKER/0/0';
        $this->fieldDefs[] = 'broker.country_code/1186/BROKER/0/0';
        $this->fieldDefs[] = 'broker.account/1179/BROKER/0/0';
        $this->fieldDefs[] = 'broker.id/1187/BROKER/0/0';

        // Document Fields
        $this->fieldDefs[] = 'documents_description/2396/DOCUMENTS/0/0';
        $this->fieldDefs[] = 'documents_flag/190/DOCUMENTS/0/0';

        // ETD (FedEx Electronic Trade Document)
        $this->fieldDefs[] = 'etd_indicator/2805/ETD/0/0';
        $this->fieldDefs[] = 'post_shipment_document_indicator/7705/ETD/0/0';
        //
        // Commodity Fields
        $this->fieldDefs[] = 'commercial_invoice_comments/418/COMMODITY/0/0';
        $this->fieldDefs[] = 'contents.*.description/79/MANUAL/0/0';
        // $this->fieldDefs[] = 'contents.*.weight_uom/0/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.uom/414/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.unit_value/1030/MANUAL/100/100';
        $this->fieldDefs[] = 'contents.*.country_of_manufacture/80/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.unit_weight/77/MANUAL/10/0';
        $this->fieldDefs[] = 'contents.*.total_value/78/MANUAL/100/100';
        $this->fieldDefs[] = 'contents.*.harmonized_code/81/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.quantity/82/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.part_number/1275/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.export_license/83/MANUAL/0/0';
        $this->fieldDefs[] = 'contents.*.export_license_date/84/MANUAL/0/0';
        //
        // Dangerous Goods Flags
        $this->fieldDefs[] = 'hazardous/0/DGOODS/0/0';                          // Dummy item to trigger DGOODS
        $this->fieldDefs[] = 'hazard_flag/1331/MANUAL/0/0';                       // Option triggered by hazardous
        $this->fieldDefs[] = 'hazard_class/492/MANUAL/0/0';                       // Option triggered by hazardous
        $this->fieldDefs[] = 'hazard_excepted_qty/1669/MANUAL/0/0';               // Option triggered by hazardous
        $this->fieldDefs[] = 'hazard_commodity_count/1932/MANUAL/0/0';            // Option triggered by hazardous
        $this->fieldDefs[] = 'hazard_name_of_signatory/1918/MANUAL/0/0';          // Option triggered by hazardous
        $this->fieldDefs[] = 'hazard_place_of_signatory/1922/MANUAL/0/0';         // Option triggered by hazardous
        $this->fieldDefs[] = 'hazard_title_of_signatory/485/MANUAL/0/0';          // Option triggered by hazardous

        // ********************************************** //
        $this->fieldDefs[] = 'label_specification.label_size/187/PRINTER/0/0';    // Size and type
        $this->fieldDefs[] = 'label_specification.printer_type/1282/MANUAL/0/0';  // Output triggered by label_size
        $this->fieldDefs[] = 'label_specification.label_path/537/MANUAL/0/0';     // Output triggered by label_size
        // ********************************************** //
        $this->fieldDefs[] = 'EOT/99/MANUAL/0/0';

        /*
         * *******************************************
         * Decode all field definitions
         * *******************************************
         */
        foreach ($this->fieldDefs as $value) {
            $tmp = explode('/', $value);
            if (isset($tmp[0])) {
                $this->fldno[$tmp[0]] = $tmp[1];
            } else {
                $this->fldno[$tmp[0]] = '';
            }

            if (isset($tmp[1])) {
                $this->group[$tmp[0]] = $tmp[2];
            } else {
                $this->group = '';
            }

            // Set Kg Multipliers
            if (isset($tmp[2])) {
                $this->mult['kg'][$tmp[1]] = $tmp[3];
            } else {
                $this->mult['kg'][$tmp[1]] = 0;
            }

            // Set LB Multipliers
            if (isset($tmp[3])) {
                $this->mult['lb'][$tmp[1]] = $tmp[4];
            } else {
                $this->mult['lb'][$tmp[1]] = 0;
            }
        }
    }

    public function multiplier($uom, $key, $value, $mode = 'enable')
    {
        $mult = 0;
        if (isset($this->mult[$uom][$this->fldno[$key]])) {
            $mult = $this->mult[$uom][$this->fldno[$key]];
        }

        if ($mult > 0 && ! is_numeric($value)) {
            $msg = "uom: $uom, key: $key, value: $value, mode: $mode";
            mail('it@antrim.ifsgroup.com', "Error in CarrierAPI\FedexSettings - multiplier", $msg);
        }

        if ($mult > 0) {
            if ($mode == 'enable') {
                $value = $value * $mult;
            } else {
                $value = $value / $mult;
            }
        }

        return $value;
    }
}
