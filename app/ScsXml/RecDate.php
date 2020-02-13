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
class RecDate extends SCSTable
{
    public function __construct()
    {
        $this->tableName = 'rec-date';

        $this->attributes = [
            'rec-id' => '',
            'address-code' => '',
            'comments' => '',
            'contact-type' => '',
            'date-order' => '',
            'date-type' => '',
            'date-value' => '',
            'line-no' => '',
            'log-isl' => '',
            'number' => '',
            'record-link' => '',
            'rec-type' => '',
            'syn-field' => '',
            'syn-table' => '',
            'time-value' => '',
        ];
    }
}
