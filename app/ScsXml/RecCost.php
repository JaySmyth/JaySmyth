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
class RecCost extends SCSTable {

    public function __construct() {

        $this->tableName = "rec-cost";

        $this->attributes = [
            "rec-id" => "",
            "balance-cc" => "",
            "balance-ec" => "",
            "base-cost-value" => "",
            "base-supp-exchange" => "",
            "calc-code" => "",
            "charge-order" => "",
            "charge-type" => "",
            "charge-currency" => "",
            "consol-cost" => "",
            "cost-base-exchange" => "",
            "costcentre" => "",
            "cost-currency" => "",
            "cost-entry" => "",
            "cost-period" => "",
            "cost-processed" => "",
            "cost-rate" => "",
            "cost-source" => "",
            "cost-value" => "",
            "cost-yearno" => "",
            "date-created" => "",
            "description" => "",
            "euro-exchange" => "",
            "euro-value" => "",
            "expensecode" => "",
            "fully-matched" => "",
            "in-dispute" => "",
            "line-no" => "",
            "matched-amount" => "",
            "matched-currency" => "",
            "matched-period" => "",
            "matched-yearno" => "",
            "matched-year-period" => "",
            "maximum-value" => "",
            "minimum-value" => "",
            "notes" => "",
            "orig-id" => "",
            "orig-line" => "",
            "orig-link" => "",
            "po-line" => "",
            "po-number" => "",
            "po-record" => "",
            "posting-batch" => "",
            "record-link" => "",
            "scs-processed" => "",
            "supplier" => "",
            "supplier-currency" => "",
            "supplier-ref" => "",
            "supplier-value" => "",
            "trans-date" => ""
        ];
    }

}
