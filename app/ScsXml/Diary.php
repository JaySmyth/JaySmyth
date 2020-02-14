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
class Diary extends SCSTable
{
    public function __construct()
    {
        $this->tableName = 'diary';

        $this->attributes = [
            'rec-id' => '',
            'event-code' => '',
            'on-date' => '',
            'at-time' => '',
            'event-date' => '',
            'event-time' => '',
            'event-text' => '',
            'tracking' => '',
            'tracking-desc' => '',
        ];
    }
}
