<?php

namespace App\ScsXml;

use App\Models\Carrier;
use App\Models\ChargeCodes;
use App\Models\Company;
use App\Models\Department;
use App\Models\Package;
use App\Models\Service;
use App\Models\Shipment;
use App\Pricing\Pricing;
use App\ScsXml\Context;
use App\ScsXml\DocAdds;
use App\ScsXml\Groupage;
use App\ScsXml\Job;
use App\ScsXml\JobCol;
use App\ScsXml\JobDel;
use App\ScsXml\JobDims;
use App\ScsXml\JobHdr;
use App\ScsXml\JobLine;
use App\ScsXml\RecChg;
use App\ScsXml\RecCost;
use App\ScsXml\RecJny;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ISLEDI.
 *
 * @author gmcbroom
 */
class ISLEDI
{
    public $context;
    public $groupage;
    public $job;
    public $shipment;
    public $company;
    public $carrier;
    private $service;
    private $department;
    private $packageSummary;
    private $user;

    public function __construct($email = '')
    {
        $this->user = strstr($email, '@', true);
        $this->context = new Context($email);
        $this->groupage = new Groupage();
        $this->job;
    }

    public function createXMLSalesInvoice($shipments)
    {
        foreach (Shipment::whereIn('id', $shipments)->cursor() as $shipment) {

            /*
             * *********************************
             *  Get Costing/ Pricing information
             * *********************************
             */
            if ($shipment->quoted == null) {

                // Shipment not priced so try to price
                $prices = $shipment->price();

                // If able to price, then update shipment record
                if ($prices['errors'] == []) {
                    $shipment->quoted = json_encode($prices);
                }
            }

            $prices = json_decode($shipment->quoted, true);

            /*
             * *********************************
             * If OK to Generate Invoice
             * *********************************
             */
            $okToGenerate = $this->isOkToGenerate($shipment, $prices);
            if ($okToGenerate) {
                $this->shipment = $shipment;
                $this->carrier = Carrier::find($shipment->carrier_id);
                $this->company = Company::find($shipment->company_id);
                $this->department = Department::find($this->shipment->department_id);

                $job = new Job();

                // Create Job Details
                $job->setJobHdr($this->setJobHdr(new JobHdr()));
                $job->setJobLine($this->setJobLine(new JobLine));
                $job->setJobCol($this->setJobCol(new JobCol));
                $job->setJobDel($this->setJobDel(new JobDel));

                // Add Consignor/ Consignee addresses and dims
                $job->addAddress($this->setDocAdds(new DocAdds(), 'CONSEE'));
                $job->addAddress($this->setDocAdds(new DocAdds(), 'CONSOR'));

                $this->summarizePackages();
                foreach ($this->packageSummary as $package) {
                    $job->addDims($this->setJobDims(new JobDims(), $package));
                }

                // Department specific actions
                switch ($this->department->code) {

                    case 'IFCEX':
                        // Add Flight Details
                        $job->setRecJny($this->setRecJny(new RecJny));
                        break;

                    default:
                        break;
                }

                // Add Costs
                if (isset($prices['costs']) && ! empty($prices['costs'])) {
                    foreach ($prices['costs'] as $charge) {
                        $job->addCost($this->setRecCost($charge));
                    }
                }

                // Add Sales
                if (isset($prices['sales']) && ! empty($prices['sales'])) {
                    foreach ($prices['sales'] as $charge) {
                        $job->addCharge($this->setRecChg($charge, $prices['sales_vat_code']));
                    }
                }

                // store job
                $this->job[] = $job;
            }
        }

        return $this->toXML();
    }

    public function isOkToGenerate($shipment, $prices)
    {

        /*
         * *********************************
         * Check if OK to Generate Invoice
         * *********************************
         */
        $this->service = Service::find($shipment->service_id);

        // If the service is IPF, then we won't be able to
        // price it but we should still create an SCS Job.
        if ($this->service->code == 'ipf') {
            return true;
        }

        // If the service has been created with IFS as shipper
        // then we won't be able to price it but we should still
        // create an SCS Job.
        if ($shipment->company_id == '4') {
            return true;
        }

        // Not Priced or priced with errors so cannot Invoice
        if (! isset($prices) || ($prices['errors'] != [])) {
            return false;
        }

        // Sales price zero
        if (isset($prices['shipping_charge']) && $prices['shipping_charge'] == '0') {
            return false;
        }

        // Cost zero
        if (isset($prices['shipping_cost']) && $prices['shipping_cost'] == '0') {

            // Zero costs not allowed
            if (! $this->service->allow_zero_cost) {
                return false;
            }
        }

        return true;
    }

    private function setJobHdr($jobHdr)
    {
        $chargeableWeight = ($this->shipment->weight > $this->shipment->volumetric_weight ? $this->shipment->weight : $this->shipment->volumetric_weight);

        $department = Department::find($this->shipment->department_id);

        $jobHdr->setAttribute('company', $department->scs_company_code);
        $jobHdr->setAttribute('sysuser', $this->user);
        $jobHdr->setAttribute('job-date', $this->shipment->ship_date->format('d-m-Y'));
        $jobHdr->setAttribute('job-dept', $department->code);
        $jobHdr->setAttribute('job-office', 'BFS');
        $jobHdr->setAttribute('job-route', Service::find($this->shipment['service_id'])->scs_job_route);
        $jobHdr->setAttribute('address-code', $this->company->scs_code);
        $jobHdr->setAttribute('carrier-code', $this->carrier->scs_carrier_code);
        $jobHdr->setAttribute('invoicee', $this->company->scs_code);

        $jobHdr->setAttribute('load-type', 'MANUAL');
        $jobHdr->setAttribute('bol-copy', 0);

        if ($this->company->legacy_invoice) {

            // If Carrier Consignment Number is numeric then use it as AWB no.
            if (is_numeric($this->shipment->carrier_consignment_number)) {
                $jobHdr->setAttribute('mawb-char', $this->shipment->carrier_consignment_number);
                $jobHdr->setAttribute('product-desc', '{AWB:'.$this->shipment->consignment_number.'}');
                $jobHdr->setAttribute('cust-ref', $this->shipment->shipment_reference.'/'.$this->shipment->carrier_consignment_number);                     // Customer Ref
            } else {
                $jobHdr->setAttribute('mawb-char', $this->shipment->consignment_number);
                $jobHdr->setAttribute('product-desc', '{AWB:'.$this->shipment->carrier_consignment_number.'}');
                $jobHdr->setAttribute('cust-ref', $this->shipment->shipment_reference);                     // Customer Ref
            }
        } else {
            $jobHdr->setAttribute('mawb-char', $this->shipment->consignment_number);
            $jobHdr->setAttribute('product-desc', '{AWB:'.$this->shipment->carrier_consignment_number.'}');
            $jobHdr->setAttribute('cust-ref', $this->shipment->shipment_reference);                     // Customer Ref
        }

        $jobHdr->setAttribute('pieces', $this->shipment->pieces);
        $jobHdr->setAttribute('package-type', 'PIECES');
        $jobHdr->setAttribute('cube', ($this->shipment->volumetric_weight * $this->shipment->volumetric_divisor) / 1000000);
        $jobHdr->setAttribute('kgs-weight', $this->shipment->weight);
        $jobHdr->setAttribute('vol-weight', $this->shipment->volumetric_weight);
        $jobHdr->setAttribute('chg-weight', $chargeableWeight);
        $jobHdr->setAttribute('entered-wgt', $this->shipment->weight);
        $jobHdr->setAttribute('entered-cube', ($this->shipment->volumetric_weight * $this->shipment->volumetric_divisor) / 1000000);
        $jobHdr->setAttribute('wgt-type', $this->shipment->weight_uom.'s');
        $jobHdr->setAttribute('terms-code', strtoupper($this->shipment->terms_of_sale));
        $jobHdr->setAttribute('cw-divisor', $this->shipment->volumetric_divisor / 1000);
        $jobHdr->setAttribute('job-country-code', $this->shipment->recipient_country_code);

        return $jobHdr;
    }

    private function setJobLine($jobLine)
    {
        $weightTypes = ['kg' => 'kgs', 'lb' => 'lbs'];
        $chargeableWeight = ($this->shipment->weight > $this->shipment->volumetric_weight ? $this->shipment->weight : $this->shipment->volumetric_weight);

        if ($this->company->legacy_invoice) {
            $jobLine->setAttribute('contacts-ref', $this->shipment->carrier_consignment_number);        // SCS uses this for docketno for IFCUK
            $jobLine->setAttribute('shippers-ref', $this->shipment->shipment_reference.'/'.$this->shipment->carrier_consignment_number);                // Customer Ref
        } else {
            $jobLine->setAttribute('contacts-ref', $this->shipment->consignment_number);                // SCS uses this for docketno for IFCUK
            $jobLine->setAttribute('shippers-ref', $this->shipment->shipment_reference);                // Customer Ref
        }
        $jobLine->setAttribute('cargo-currency', $this->shipment->customs_value_currency_code);
        $jobLine->setAttribute('chg-wgt', $chargeableWeight);
        $jobLine->setAttribute('cube', ($this->shipment->volumetric_weight * $this->shipment->volumetric_divisor) / 1000000);
        $jobLine->setAttribute('entered-cube', ($this->shipment->volumetric_weight * $this->shipment->volumetric_divisor) / 1000000);
        $jobLine->setAttribute('cube-type', 'cbm');
        $jobLine->setAttribute('har-cargo', 'NO');
        $jobLine->setAttribute('package-type', 'PIECES');
        $jobLine->setAttribute('pieces', $this->shipment->pieces);
        $jobLine->setAttribute('kgs-wgt', $this->shipment->weight);
        $jobLine->setAttribute('vol-wgt', $this->shipment->volumetric_weight);
        $jobLine->setAttribute('wgt-type', $weightTypes[$this->shipment->weight_uom]);

        if ($this->company->legacy_invoice) {
            if (is_numeric($this->shipment->carrier_consignment_number)) {

                // If Carrier Consignment Number is numeric then use it as AWB no. and add IFS AWB here
                $jobLine->setAttribute('cargo-desc', '{AWB:'.$this->shipment->consignment_number.'}');
            } else {

                // Use IFS Consignment Number as MAWB and add Carrier here
                $jobLine->setAttribute('cargo-desc', '{AWB:'.$this->shipment->carrier_consignment_number.'}');
            }
        } else {
            $jobLine->setAttribute('cargo-desc', '{AWB:'.$this->shipment->carrier_consignment_number.'}');
        }

        return $jobLine;
    }

    private function setJobCol($jobCol)
    {
        $jobCol->setAttribute('col-date', $this->shipment->ship_date);
        $jobCol->setAttribute('contact-name', $this->shipment->sender_name);
        $jobCol->setAttribute('name', $this->shipment->sender_company_name);
        $jobCol->setAttribute('address-1', $this->shipment->sender_address1);
        $jobCol->setAttribute('address-2', $this->shipment->sender_address2);
        $jobCol->setAttribute('address-3', $this->shipment->sender_address3);
        $jobCol->setAttribute('town', $this->shipment->sender_city);
        $jobCol->setAttribute('county', $this->shipment->sender_state);
        $jobCol->setAttribute('postcode', $this->shipment->sender_postcode);
        $jobCol->setAttribute('telephone', $this->shipment->sender_telephone);
        $jobCol->setAttribute('email', $this->shipment->sender_email);
        $jobCol->setAttribute('country-code', $this->shipment->sender_country_code);

        return $jobCol;
    }

    private function setJobDel($jobDel)
    {

        // $jobDel->setAttribute('col-date', $this->shipment->ship_date);
        if ($this->shipment->recipient_company_name > '') {

            // If Company name defined set contact name & company name
            $jobDel->setAttribute('contact-name', $this->shipment->recipient_name);
            $jobDel->setAttribute('name', $this->shipment->recipient_company_name);
        } else {

            // If Company name not defined set company name to be contact name
            $jobDel->setAttribute('contact-name', '');
            $jobDel->setAttribute('name', $this->shipment->recipient_name);
        }
        $jobDel->setAttribute('address-1', $this->shipment->recipient_address1);
        $jobDel->setAttribute('address-2', $this->shipment->recipient_address2);
        $jobDel->setAttribute('address-3', $this->shipment->recipient_address3);
        $jobDel->setAttribute('town', $this->shipment->recipient_city);
        $jobDel->setAttribute('county', $this->shipment->recipient_state);
        $jobDel->setAttribute('postcode', $this->shipment->recipient_postcode);
        $jobDel->setAttribute('telephone', $this->shipment->recipient_telephone);
        $jobDel->setAttribute('email', $this->shipment->recipient_email);
        $jobDel->setAttribute('country-code', $this->shipment->recipient_country_code);

        return $jobDel;
    }

    private function setDocAdds($docAdds, $type)
    {
        $addressTypes = ['CONSEE' => 'recipient', 'CONSOR' => 'sender'];
        $addressType = $addressTypes[$type];

        $docAdds->setAttribute('address-type', $type);
        $docAdds->setAttribute('contact-name', $this->getField($addressType.'_name'));
        $docAdds->setAttribute('name', $this->getField($addressType.'_company_name'));
        $docAdds->setAttribute('address-1', $this->getField($addressType.'_address1'));
        $docAdds->setAttribute('address-2', $this->getField($addressType.'_address2'));
        $docAdds->setAttribute('address-3', $this->getField($addressType.'_address3'));
        $docAdds->setAttribute('town', $this->getField($addressType.'_city'));
        $docAdds->setAttribute('county', $this->getField($addressType.'_state'));
        $docAdds->setAttribute('country-code', $this->getField($addressType.'_country_code'));
        $docAdds->setAttribute('email', $this->getField($addressType.'_email'));
        $docAdds->setAttribute('postcode', $this->getField($addressType.'_postcode'));
        $docAdds->setAttribute('telephone', $this->getField($addressType.'_telephone'));

        return $docAdds;
    }

    public function getfield($fieldName)
    {
        return $this->shipment->$fieldName;
    }

    public function summarizePackages()
    {
        $weightUom = ['kg' => 'kgs', 'lb' => 'lbs'];
        $dimsUom = ['cm' => 'C', 'in' => 'I', 'inch' => 'I'];
        $this->packageSummary = [];

        // Get Shipment Packages
        $packages = Package::where('shipment_id', $this->shipment->id)->get();

        // Summarize them
        foreach ($packages as $package) {
            $size = $package->length.$package->width.$package->height;

            if (isset($this->packageSummary[$size])) {
                $this->packageSummary[$size]['pieces']++;
            } else {
                $this->packageSummary[$size]['length'] = $package->length;
                $this->packageSummary[$size]['width'] = $package->width;
                $this->packageSummary[$size]['height'] = $package->height;
                $this->packageSummary[$size]['unit-type'] = $dimsUom[$this->shipment->dims_uom];
                $this->packageSummary[$size]['weight'] = $package->weight;
                $this->packageSummary[$size]['pieces'] = '1';
                $this->packageSummary[$size]['weight-type'] = $weightUom[$this->shipment->weight_uom];
                $this->packageSummary[$size]['package-type'] = $package->packaging_code;
                $this->packageSummary[$size]['cube'] = round($package->length * $package->width * $package->height / 1000000, 4);
            }
        }
    }

    private function setjobDims($jobDims, $package)
    {
        $jobDims->setAttribute('entered-length', $package['length']);
        $jobDims->setAttribute('entered-width', $package['width']);
        $jobDims->setAttribute('entered-height', $package['height']);
        $jobDims->setAttribute('entered-weight', $package['weight']);
        $jobDims->setAttribute('entered-unit-type', $package['unit-type']);
        $jobDims->setAttribute('weight-type', $package['weight-type']);
        // $jobDims->setAttribute("package-type", $package['package-type']);
        $jobDims->setAttribute('package-type', 'PIECES');
        $jobDims->setAttribute('pieces', $package['pieces']);
        $jobDims->setAttribute('entered-cube', $package['cube'] * $package['pieces']);

        return $jobDims;
    }

    private function setRecJny($recJny)
    {

        // $recJny->setAttribute('airline-prefix', '');
        $recJny->setAttribute('char-prefix', substr($this->shipment->consignment_number, 0, 3));
        $recJny->setAttribute('flight-by-1', $this->carrier->scs_carrier_code);
        $recJny->setAttribute('flight-prefix-1', $this->carrier->scs_carrier_code);
        $recJny->setAttribute('mawb', substr($this->shipment->consignment_number, 3));
        $recJny->setAttribute('mawb-date', $this->shipment->ship_date);
        // $recJny->setAttribute('port-of-loading', substr($this->shipment->ship_date,3));

        return $recJny;
    }

    private function setRecCost($charge)
    {
        if (! isset($charge['description'])) {
            $charge['description'] = $charge['code'];
        }

        $chargeCode = ChargeCodes::where('code', $charge['code'])->first();
        if ($chargeCode) {
            $chargeType = $chargeCode->scs_code;
        } else {
            $chargeType = 'MIS';
        }

        // XDP bundle the Residential charge into Freight costs
        if ($this->shipment['service_id'] == '76' && $chargeType == 'RES') {
            $chargeType = 'FRT';
        }

        // Identify Supplier SCS Account
        $service = Company::find($this->shipment['company_id'])->services()->where('services.id', $this->shipment['service_id'])->first();
        if (isset($service->pivot->scs_account) && $service->pivot->scs_account > '') {

            // Use Customer specific Supplier SCS cost account
            $supplierAccount = $service->pivot->scs_account;
        } else {

            // Use Service Default SCS cost account
            $supplierAccount = Service::find($this->shipment['service_id'])->scs_account_code;
        }

        $xml = new self();
        $recCost = new RecCost();
        $recCost->setAttribute('supplier', $supplierAccount);
        $recCost->setAttribute('supplier-currency', $this->shipment->cost_currency);
        $recCost->setAttribute('charge-type', $chargeType);
        $recCost->setAttribute('description', $charge['description']);
        $recCost->setAttribute('cost-rate', $charge['value']);
        $recCost->setAttribute('cost-currency', $this->shipment->cost_currency);
        $recCost->setAttribute('charge-currency', $this->shipment->cost_currency);
        $recCost->setAttribute('calc-code', 'LS');

        return $recCost;
    }

    private function setRecChg($charge, $vatCode)
    {
        $chargeCode = ChargeCodes::where('code', $charge['code'])->first();

        if ($chargeCode) {
            $chargeType = $chargeCode->scs_code;
        } else {
            $chargeType = 'MIS';
        }

        $recChg = new RecChg();
        $recChg->setAttribute('invoicee', Company::find($this->shipment->company_id)->scs_code);
        $recChg->setAttribute('charge-currency', $this->shipment->sales_currency);
        $recChg->setAttribute('charge-type', $chargeType);
        $recChg->setAttribute('description', $charge['description']);
        $recChg->setAttribute('charge-rate', $charge['value']);
        $recChg->setAttribute('calc-code', 'LS');
        $recChg->setAttribute('prepaid-collect', 'P');
        $recChg->setAttribute('invoice-currency', $this->shipment->sales_currency);
        $recChg->setAttribute('vat-code', $vatCode);

        return $recChg;
    }

    public function toXML()
    {
        $xml = $this->getXMLHeadings();
        $xml .= $this->context->toXML();
        $xml .= $this->groupage->toXML();

        if ($this->job) {
            foreach ($this->job as $job) {
                $xml .= $job->toXML();
            }
        }

        $xml .= '</ISLEDI>';

        return $xml;
    }

    private function getXMLHeadings()
    {
        return '<?xml version="1.0" encoding="utf-8"?><ISLEDI xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
    }
}
