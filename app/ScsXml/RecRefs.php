<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ScsXml;

/**
 * Description of DocAdds.
 *
 * @author gmcbroom
 */
class RecRefs extends SCSTable
{
    public function __construct()
    {
        $this->tableName = 'rec-refs';

        $this->attributes = [
            'rec-id' => '',
            'address-code' => '',
            'contact-type' => '',
            'line-no' => '',
            'log-isl' => '',
            'number' => '',
            'record-link' => '',
            'rec-type' => '',
            'ref-type' => '',
            'ref-value' => '',
            'syn-field' => '',
            'syn-table' => '',
        ];
    }
}
