<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ScsXml;

/**
 * Description of DocAdds
 *
 * @author gmcbroom
 */
class RecChg extends SCSTable {

    public function __construct() {

        $this->tableName = "rec-chg";

        $this->attributes = [
            "rec-id" => "",
            "all-in" => "",
            "base-inv-exchange" => "",
            "base-sales-value" => "",
            "base-vat-value" => "",
            "calc-code" => "",
            "charge-currency" => "",
            "charge-entry" => "",
            "charge-order" => "",
            "charge-rate" => "",
            "charge-type" => "",
            "charge-value" => "",
            "chg-base-exchange" => "",
            "chg-source" => "",
            "costcentre" => "",
            "date-created" => "",
            "description" => "",
            "euro-exchange" => "",
            "euro-value" => "",
            "euro-vat" => "",
            "expensecode" => "",
            "invoice-currency" => "",
            "invoicee" => "",
            "invoice-number" => "",
            "invoice-value" => "",
            "invoice-vat" => "",
            "inv-period" => "",
            "inv-yearno" => "",
            "line-no" => "",
            "maximum-value" => "",
            "minimum-value" => "",
            "notes" => "",
            "orig-id" => "",
            "orig-line" => "",
            "orig-link" => "",
            "prepaid-collect" => "",
            "rec-no" => "",
            "record-link" => "",
            "scs-processed" => "",
            "trans-date" => "",
            "vat-code" => "",
            "vat-exchange" => "",
            "vat-rate" => ""
        ];
    }

}
