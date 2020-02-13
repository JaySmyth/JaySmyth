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
class DocAdds extends SCSTable
{
    public function __construct()
    {
        $this->tableName = 'doc-adds';

        $this->attributes = [
            'rec-id' => '',
            'address-1' => '',
            'address-2' => '',
            'address-3' => '',
            'address-code' => '',
            'address-type' => '',
            'contact-name' => '',
            'country-code' => '',
            'county' => '',
            'email' => '',
            'fax' => '',
            'keyname' => '',
            'line-no' => '',
            'name' => '',
            'postcode' => '',
            'record-link' => '',
            'reference' => '',
            'telephone' => '',
            'telex' => '',
            'town' => '',
        ];
    }
}
