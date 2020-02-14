<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobDims.
 *
 * @author gmcbroom
 */
class JobDims extends SCSTable
{
    public function __construct()
    {
        $this->tableName = 'job-dims';

        $this->attributes = [
            'job-id' => '',
            'cube' => '',
            'cube-type' => '',
            'dim-no' => '',
            'entered-cube' => '',
            'entered-height' => '',
            'entered-length' => '',
            'entered-unit-type' => '',
            'entered-weight' => '',
            'entered-width' => '',
            'ft-cube' => '',
            'height' => '',
            'kgs-weight-nett' => '',
            'kgs-wgt' => '',
            'lbs-weight-nett' => '',
            'lbs-wgt' => '',
            'length' => '',
            'line-no' => '',
            'package-type' => '',
            'pieces' => '',
            'weight-type' => '',
            'width' => '',
        ];
    }
}
