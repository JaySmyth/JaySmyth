<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ScsXml;

use Auth;

/**
 * Description of DocAdds.
 *
 * @author gmcbroom
 */
class Context extends SCSTable
{
    public function __construct($email = '')
    {
        $this->tableName = 'Context';

        $this->attributes = [
        'Interface' => 'IFSWEB',
        'Notify_email' => '',
        'Reject_email' => '',
        'Action' => '',
        'Reference-type' => '',
        'PreProcess' => '',
        'Reference' => '',
        'Reference' => '',
        'Customer' => '',
        'Reference-type' => '',
        'PostProcess' => '',
        ];

        $this->setAttribute('Notify_email', $email);
        $this->setAttribute('Reject_email', $email);
    }
}
