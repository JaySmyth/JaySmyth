<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ScsXml;

/**
 * Description of RecCont
 *
 * @author gmcbroom
 */
class RecCont extends SCSTable {

    public function __construct() {

        $this->tableName = "rec-cont";

        $this->attributes = [
            "rec-id" => "",
            "bill-of-lading" => "",
            "container-code" => "",
            "container-number" => "",
            "cube" => "",
            "cube-type" => "",
            "date" => "",
            "entered-cube" => "",
            "entered-wgt" => "",
            "ft-cube" => "",
            "fumigation" => "",
            "height" => "",
            "job-line" => "",
            "kgs-weight-nett" => "",
            "kgs-wgt" => "",
            "lbs-weight-nett" => "",
            "lbs-wgt" => "",
            "length" => "",
            "line-no" => "",
            "max-temp" => "",
            "min-temp" => "",
            "number" => "",
            "og-height-1" => "",
            "og-height-2" => "",
            "og-length-1" => "",
            "og-length-2" => "",
            "og-width-1" => "",
            "og-width-2" => "",
            "original-line" => "",
            "pallets" => "",
            "pieces" => "",
            "qty" => "",
            "record-link" => "",
            "seal-number" => "",
            "seal-number2" => "",
            "tare-wgt" => "",
            "temp-max" => "",
            "temp-min" => "",
            "temp-type" => "",
            "UCN" => "",
            "wgt-type" => "",
            "width" => "",
        ];
    }

}
