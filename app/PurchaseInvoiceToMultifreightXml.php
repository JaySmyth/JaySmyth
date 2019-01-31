<?php

namespace App;

use App\Legacy\array2xml;

trait PurchaseInvoiceToMultifreightXml
{

    protected $schema = 'xsi:noNamespaceSchemaLocation="PI_Invoice.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
    protected $expenseCodes = array('IFCEX' => '092015', 'IFCIM' => '093015', 'IFCUK' => '095015');
    protected $mfMatchingArray = array();
    protected $overheadArray = array();
    protected $key = 0;

    /**
     * Get Multifreight XML representation of this invoice.
     *
     * @return string
     */
    public function getMultifreightXml()
    {
        return $this->array2xml($this->buildArray());
    }

    /**
     * Convert an array to XML.
     *
     * @param array $array
     * @return string
     */
    private function array2xml($array)
    {
        $xml = new array2xml();
        $xml->setArray($array);
        return $xml->outputXML('return');
    }

    /**
     * Drop leading zeros from UPS invoice numbers.
     *
     * @param type $invoice
     * @return string
     */
    private function getFormattedInvoiceNumber()
    {
        switch (strtoupper($this->carrier->code)) {
            case 'UPS':
                return substr($this->invoice_number, 4);

            default:
                return $this->invoice_number;
        }
    }

    /**
     * Build the main array that will be converted to XML.
     *
     * @param type $bulk
     * @return array
     */
    private function buildArray()
    {
        foreach ($this->lines as $line) {

            // Add combined/discounted FRT charge to array
            $this->addFreightChargeToArray($line);

            // Add other charges to array
            foreach ($line->charges as $charge) {
                if ($charge->scs_charge_code != 'FRT') {
                    $this->addToArray($line, $charge);
                }
            }
        }

        // Build the context array
        $contextArray = array('Context' => array('ifs' => $this->getFormattedInvoiceNumber()));

        // Merge arrays together
        $multifreightImport = array_merge($contextArray, $this->getPiPostingArray(), $this->getPiHeaderArray(), $this->getPiVatArray(), $this->mfMatchingArray, $this->overheadArray);

        // Build final array
        if ($this->type != 'F') {
            return array("PI_Invoice $this->schema" => $multifreightImport);
        }

        return array('Multifreight' => array("PI_Invoice $this->schema" => $multifreightImport));
    }

    /**
     * Add combined (and discounted) FRT charge to the array.
     *
     * @param type $line
     */
    private function addFreightChargeToArray($line)
    {
        if ($line->total_freight > 0) {
            $charge = $line->charges->where('scs_charge_code', '=', 'FRT')->first();
            $this->addToArray($line, $charge, $line->total_freight);
        }
    }

    /**
     * Add a charge to the relevant array element.
     *
     * @param type $line
     * @param type $charge
     */
    private function addToArray($line, $charge, $actualBilledAmount = false)
    {
        // Don't add zero values or VAT charges if Freight invoice  (except DHL - freight and duty combined in same invoice)
        if ($charge->actual_billed_amount <= 0 || ($this->type == 'F' && $charge->scs_charge_code == 'CDV' && $this->carrier->id != 5)) {
            return false;
        }

        if (preg_match('/[a-zA-Z]{6}[0-9]{8}/', $line->scs_job_number)) {
            $this->addToMfMatchingArray($line, $charge, $actualBilledAmount);
        } else {
            $this->addToOverheadArray($line, $charge, $actualBilledAmount);
        }

        $this->key++;
    }

    /**
     * Add a known job to the MF_Matching element.
     *
     * @param type $charge
     * @param int $i
     */
    private function addToMfMatchingArray($line, $charge, $actualBilledAmount)
    {
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Reference_Type'] = 'JOBNO';
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Department'] = substr($line->scs_job_number, 0, 5);
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Reference'] = $line->scs_job_number;
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Charge_Type'] = $charge->scs_charge_code;
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Charge_Source'] = 'OCR';
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Fully_Match'] = 'NO';
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Matched_Amount'] = ($actualBilledAmount) ? $actualBilledAmount : $charge->actual_billed_amount;
        $this->mfMatchingArray[$this->key . 'MF_Matching']['MF_Match_VAT_Code'] = $line->scs_vat_code;
        $this->mfMatchingArray[$this->key . 'MF_Matching']['PO_Number'] = null;
    }

    /**
     * Add unknown job to Overhead element.
     *
     * @param type $charge
     * @param int $i
     */
    private function addToOverheadArray($line, $charge, $actualBilledAmount)
    {
        $this->overheadArray[$this->key . 'Overhead']['MF_Department'] = 'IFCEX';
        $this->overheadArray[$this->key . 'Overhead']['Charge_Type'] = $charge->scs_charge_code;
        $this->overheadArray[$this->key . 'Overhead']['Description'] = 'Inv:' . $this->getFormattedInvoiceNumber();
        $this->overheadArray[$this->key . 'Overhead']['Currency_Amount'] = ($actualBilledAmount) ? $actualBilledAmount : $charge->actual_billed_amount;
        $this->overheadArray[$this->key . 'Overhead']['Exchange_Rate'] = '1.0000';
        $this->overheadArray[$this->key . 'Overhead']['Base_Amount'] = ($actualBilledAmount) ? $actualBilledAmount : $charge->actual_billed_amount;
        $this->overheadArray[$this->key . 'Overhead']['VAT_Code'] = $line->scs_vat_code;
        $this->overheadArray[$this->key . 'Overhead']['VAT_Rate'] = 20;
        $this->overheadArray[$this->key . 'Overhead']['Cost_Centre'] = '02';
        $this->overheadArray[$this->key . 'Overhead']['Expense_Code'] = $this->expenseCodes['IFCEX'];
    }

    /**
     * Build the PI_Header array.
     */
    private function getPiHeaderArray()
    {
        $piHeaderArray = array();
        $piHeaderArray['PI_Header']['Supplier_Code'] = $this->scs_supplier_code;
        $piHeaderArray['PI_Header']['Currency'] = $this->currency_code;
        $piHeaderArray['PI_Header']['Invoice_Number'] = $this->getFormattedInvoiceNumber();
        $piHeaderArray['PI_Header']['Invoice_Date'] = $this->date->format('Y-m-d');
        $piHeaderArray['PI_Header']['Invoice_Total'] = $this->total;
        $piHeaderArray['PI_Header']['PO_number'] = '';
        $piHeaderArray['PI_Header']['Due_Date'] = '';
        $piHeaderArray['PI_Header']['Document_Source'] = '';
        return $piHeaderArray;
    }

    /**
     * Build PI_VAT array.
     *
     * @return array
     */
    private function getPiVatArray()
    {
        $piVatArray = array();

        if ($this->total_non_taxable > 0) {
            $piVatArray [0 . 'PI_VAT']['VAT_Code'] = 'Z';
            $piVatArray [0 . 'PI_VAT']['VAT_Goods'] = $this->total_non_taxable;
            $piVatArray [0 . 'PI_VAT']['VAT_Amount'] = '0.00';
        }

        if ($this->total_taxable > 0) {
            $piVatArray [1 . 'PI_VAT']['VAT_Code'] = '1';
            $piVatArray [1 . 'PI_VAT']['VAT_Goods'] = number_format($this->total_taxable, 2, '.', '');
            $piVatArray [1 . 'PI_VAT']['VAT_Amount'] = number_format($this->vat, 2, '.', '');
        }

        return $piVatArray;
    }

    /**
     * Build the PI_Posting array.
     *
     * @return array
     */
    private function getPiPostingArray()
    {
        return array(
            'PI_Posting' => array(
                'MF_Department' => 'IFCEX',
                'Action' => 'POST',
                'Authorised_by' => 'IFS Global Logistics',
                'Source_Document_Location' => '',
                'Notes' => '',
                'Notify_Email' => null
            )
        );
    }

}
