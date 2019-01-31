<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ScsXml;

use App\ScsXml\GrpHdr;

/**
 * Description of Job
 *
 * @author gmcbroom
 */
class Groupage {

    private $grpHdr;

    public function __construct() {

        $this->grpHdr = new GrpHdr();
    }

    public function toXML() {

        $xml = "<Groupage>";
        $xml .= $this->buildXML('grpHdr');
        $xml .= "</Groupage>";

        return $xml;
    }

    /**
     * Returns XML for an object or array of objects
     * 
     * @param type $tableName
     * @return type
     */
    public function buildXML($tableName) {

        $xml = '';

        if ($this->$tableName->table) {
            
            if (is_array($this->$tableName->table)) {

                foreach ($this->$tableName->table as $table) {

                    $xml .= $table->toXML();
                }
            } else {

                $xml .= $table->toXML();
            }
        }

        return $xml;
    }

    /**
     * Create Objects for GrpHdr table
     * 
     * @param type $tableName
     * @return \App\ScsXml\JobHdr
     */
    public function create($tableName) {

        switch (strtolower($tableName)) {

            case 'grphdr':
                return new JobHdr();
                break;

            default:
                break;
        }
    }

}
