<?php

namespace App\ScsXml;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Context.
 *
 * @author gmcbroom
 */
class ISLContext
{
    private $interface = '';
    private $notifyEmail = '';
    private $rejectEmail = '';
    private $action = '';
    private $referenceType = '';
    private $preProcess = '';
    private $references = [];
    private $customer = '';
    private $postProcess = '';

    public function __construct()
    {
        $this->setPreProcess(new PreProcess);
    }

    public function setPreProcess($preProcess)
    {
        $this->preProcess = $preProcess;
    }

    /**
     * @param Reference $reference
     *
     * @return Context
     */
    public function addReference(Reference $reference)
    {
        $references = $this->getReferences();
        $references[] = $reference;
        $this->setReferences($references);

        return $this;
    }

    /**
     * @param Reference[] $references
     *
     * @return Context
     */
    public function setReferences(array $references)
    {
        $this->references = $references;

        return $this;
    }

    /**
     * @return Reference[]
     */
    public function getReferences()
    {
        return $this->reference;
    }

    /**
     * @param $interface
     *
     * @return Context
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;

        return $this;
    }

    /**
     * @return interface
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * @param $email
     *
     * @return Context
     */
    public function setNotifyEmail($email)
    {
        $this->notifyEmail = $email;

        return $this;
    }

    /**
     * @return Notify_Email
     */
    public function getNotifyEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     *
     * @return Context
     */
    public function setRejectEmail($email)
    {
        $this->rejectEmail = $email;

        return $this;
    }

    /**
     * @return Notify_Email
     */
    public function getRejectEmail()
    {
        return $this->rejectEmail;
    }

    /**
     * @param $action
     *
     * @return Context
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $customer
     *
     * @return Context
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return $customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param $customer
     *
     * @return Context
     */
    public function setReferenceType($referenceType)
    {
        $this->referenceType = $referenceType;

        return $this;
    }

    /**
     * @return $customer
     */
    public function getReferenceType()
    {
        return $this->referenceType;
    }

    /**
     * @param $customer
     *
     * @return Context
     */
    public function setPostProcess($string)
    {
        $this->postProcess = $string;

        return $this;
    }

    /**
     * @return $customer
     */
    public function getPostProcess()
    {
        return $this->postProcess;
    }

    public function toXML()
    {
        $xml = '<Context>';
        $xml .= '<Interface>'.$this->interface.'</Interface>';
        $xml .= '<Notify_email>'.$this->notifyEmail.'</Notify_email>';
        $xml .= '<Reject_email>'.$this->rejectEmail.'</Reject_email>';
        $xml .= '<Action>'.$this->action.'</Action>';
        $xml .= '<Reference_type>'.$this->referenceType.'</Reference_type>';
        $xml .= $this->preProcess->toXML();

        foreach ($this->references as $reference) {
            $xml .= $reference->toXML();
        }

        $xml .= '<Customer>'.$this->action.'</Customer>';
        $xml .= '<Reference-type>'.$this->action.'</Reference-type>';
        $xml .= '<PostProcess>'.$this->action.'</PostProcess>';
        $xml .= '</Context>';

        return $xml;
    }
}
