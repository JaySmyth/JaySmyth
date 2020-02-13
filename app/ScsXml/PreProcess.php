<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PreProcess.
 *
 * @author gmcbroom
 */
class PreProcess
{
    private $keyField;
    private $interface;
    private $string;

    /**
     * @return PreProcess
     */
    public function setKeyfield($value)
    {
        $this->keyField = $value;

        return $this;
    }

    /**
     * @return PreProcess
     */
    public function setInterface($value)
    {
        $this->interface = $value;

        return $this;
    }

    /**
     * @return PreProcess
     */
    public function setString($value)
    {
        $this->string = $value;

        return $this;
    }

    /**
     * @return PreProcess
     */
    public function getKeyfield()
    {
        return $this->keyField;
    }

    /**
     * @return PreProcess
     */
    public function getInterface($value)
    {
        return $this->interface;
    }

    /**
     * @return PreProcess
     */
    public function getString($value)
    {
        return $this->string;
    }

    /**
     * @return XML
     */
    public function toXML()
    {
        return '<PreProcess KeyField="'.$this->keyField.'" '
                .'Interface="'.$this->interface.'">'
                .$this->string
                .'</PreProcess>';
    }
}
