<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostProcess.
 *
 * @author gmcbroom
 */
class PostProcess
{
    private $string;

    /**
     * @param type $string
     * @return PostProcess
     */
    public function setString($string)
    {
        $this->string = $string;

        return $this;
    }

    public function getString()
    {
        return $this->string;
    }

    public function toXML()
    {
        return '<PostProcess>'.$this->string.'</PostProcess>';
    }
}
