<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobDel.
 *
 * @author gmcbroom
 */
class JobDel extends SCSTable
{
    public function __construct()
    {
        $this->tableName = 'job-del';

        $this->attributes = [
            'job-id' => '',
            'address-1' => '',
            'address-2' => '',
            'address-3' => '',
            'address-code' => '',
            'avoid-times' => '',
            'bol-received' => '',
            'checked-by' => '',
            'checked-date' => '',
            'close-time' => '',
            'contact-name' => '',
            'country-code' => '',
            'county' => '',
            'cube' => '',
            'cube-type' => '',
            'date-amended' => '',
            'date-created' => '',
            'del-date' => '',
            'delivery-type' => '',
            'del-no' => '',
            'del-ref' => '',
            'del-time' => '',
            'depot' => '',
            'driver' => '',
            'driver-mobile' => '',
            'email' => '',
            'entered-cube' => '',
            'entered-weight' => '',
            'equipment' => '',
            'extra-details' => '',
            'failed' => '',
            'failure-by' => '',
            'failure-comment' => '',
            'failure-date' => '',
            'fax' => '',
            'ft-cube' => '',
            'haulage-provider' => '',
            'haulier' => '',
            'kgs-weight-nett' => '',
            'kgs-wgt' => '',
            'known-shipper' => '',
            'lbs-weight-nett' => '',
            'lbs-wgt' => '',
            'name' => '',
            'number-of-prints' => '',
            'off-quay-date' => '',
            'off-quay-time' => '',
            'open-time' => '',
            'package-type' => '',
            'pallets' => '',
            'pieces' => '',
            'pod-date' => '',
            'pod-signature' => '',
            'pod-time' => '',
            'postcode' => '',
            'printed' => '',
            'product-code' => '',
            'product-desc' => '',
            'service-failure' => '',
            'sys-ref' => '',
            'sysuser' => '',
            'sysuser-amended' => '',
            'telephone' => '',
            'telex' => '',
            'time-amended' => '',
            'time-created' => '',
            'town' => '',
            'trailer-number' => '',
            'vehicle-booked-date' => '',
            'vehicle-reg' => '',
            'weight-type' => '',
            'zone-code' => '',
        ];
    }
}
