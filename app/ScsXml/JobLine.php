<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobLine
 *
 * @author gmcbroom
 */
class JobLine extends SCSTable {

    public function __construct() {

        $this->tableName = "job-line";

        $this->attributes = [
            "job-id" => "",
            "awb-add1" => "",
            "awb-add2" => "",
            "awb-add3" => "",
            "bill-of-lading" => "",
            "call-off-ref" => "",
            "cargo-currency" => "",
            "cargo-desc" => "",
            "cargo-value" => "",
            "chg-wgt" => "",
            "commodity-item-no" => "",
            "consignee" => "",
            "consignor" => "",
            "consolidator" => "",
            "contact-name" => "",
            "contacts-ref" => "",
            "contact-telephone" => "",
            "courier-payee" => "",
            "credit-letter-text" => "",
            "cube" => "",
            "cube-type" => "",
            "delivery-date" => "",
            "entered-cube" => "",
            "entered-wgt" => "",
            "failure-comment" => "",
            "ft-cube" => "",
            "goods-received" => "",
            "handling-text" => "",
            "har-cargo" => "",
            "haz-contact-fax" => "",
            "haz-contact-name" => "",
            "haz-contact-telephone" => "",
            "haz-ems" => "",
            "haz-flashpoint" => "",
            "haz-imco" => "",
            "haz-imco-char" => "",
            "haz-imco-sub" => "",
            "haz-mfag" => "",
            "haz-neq" => "",
            "haz-nett-weight" => "",
            "haz-pack-grp" => "",
            "haz-page" => "",
            "haz-page-no" => "",
            "haz-pieces" => "",
            "haz-psn" => "",
            "haz-stowage-level" => "",
            "haz-stowage-position" => "",
            "haz-text" => "",
            "haz-text-no" => "",
            "haz-transport-category" => "",
            "haz-tunnel-code" => "",
            "haz-unno" => "",
            "kgs-weight-nett" => "",
            "kgs-wgt" => "",
            "known-shipper" => "",
            "lbs-chg" => "",
            "lbs-vol" => "",
            "lbs-weight-nett" => "",
            "lbs-wgt" => "",
            "line-no" => "",
            "loading-meters" => "",
            "marks" => "",
            "order-no" => "",
            "package-type" => "",
            "pallets" => "",
            "pe-crn" => "",
            "pieces" => "",
            "pod-date" => "",
            "pod-signature" => "",
            "pod-time" => "",
            "product-code" => "",
            "product-desc" => "",
            "service-failure" => "",
            "shippers-ref" => "",
            "special-instr" => "",
            "vol-wgt" => "",
            "wgt-type" => "",
            "whs-reference" => ""
        ];
    }

}
