<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reference
 *
 * @author gmcbroom
 */
class Reference {

    private $indexField;
    private $string;

    /**
     * @return Reference
     */
    public function setIndexfield($value) {
        $this->indexField = $value;
        return $this;
    }

    /**
     * @return Reference
     */
    public function setString($value) {
        $this->string = $value;
        return $this;
    }

    /**
     * @return indexField
     */
    public function getIndexfield() {
        return $this->indexField;
    }

    /**
     * @return string
     */
    public function getString($value) {
        return $this->string;
    }

    /**
     * @return XML
     */
    public function toXML() {
        return '<Reference IndexField="' . $this->indexField . '">' . $this->string . '</Reference>';
    }

}
