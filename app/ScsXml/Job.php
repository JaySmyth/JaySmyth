<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\ScsXml;

use App\ScsXml\Diary;
use App\ScsXml\DocAdds;
use App\ScsXml\JobCol;
use App\ScsXml\JobDel;
use App\ScsXml\JobDims;
use App\ScsXml\JobHdr;
use App\ScsXml\JobLine;
use App\ScsXml\RecChg;
use App\ScsXml\RecCont;
use App\ScsXml\RecCost;
use App\ScsXml\RecDate;
use App\ScsXml\RecRefs;

/**
 * Description of Job.
 *
 * @author gmcbroom
 */
class Job
{
    public $jobHdr;
    public $jobLine;
    public $jobCol;
    public $jobDel;
    public $recJny;
    public $recCont;
    public $jobDims;
    private $docAdds;
    private $recChg;
    private $recCost;
    private $recRefs;
    private $recDate;
    private $diary;

    public function __construct()
    {
        $this->jobHdr = new JobHdr();
        $this->jobLine = new JobLine();
        $this->jobCol = new JobCol();
        $this->jobDel = new JobDel();
        $this->recJny = [];
        $this->recCont = [];
        $this->jobDims = [];
        $this->docAdds = [];
        $this->recChg = [];
        $this->recCost = [];
        $this->recRefs = [];
        $this->recDate = [];
        $this->diary = [];
    }

    public function toXML()
    {
        $xml = '<Job>';
        $xml .= $this->buildXML('jobHdr');
        $xml .= $this->buildXML('jobLine');
        $xml .= $this->buildXML('jobCol');
        $xml .= $this->buildXML('jobDel');
        $xml .= $this->buildXML('recJny');
        $xml .= $this->buildXML('recCont');
        $xml .= $this->buildXML('jobDims');
        $xml .= $this->buildXML('docAdds');
        $xml .= $this->buildXML('recChg');
        $xml .= $this->buildXML('recCost');
        $xml .= $this->buildXML('recRefs');
        $xml .= $this->buildXML('recDate');
        $xml .= $this->buildXML('diary');
        $xml .= '</Job>';

        return $xml;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function setJobHdr($jobHdr)
    {
        $this->jobHdr = $jobHdr;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function setJobLine($jobLine)
    {
        $this->jobLine = $jobLine;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function setJobCol($jobCol)
    {
        $this->jobCol = $jobCol;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function setJobDel($jobDel)
    {
        $this->jobDel = $jobDel;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function setRecJny($recJny)
    {
        $this->recJny = $recJny;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addAddress($docAdds)
    {
        $this->docAdds[] = $docAdds;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addCharge($recChg)
    {
        $this->recChg[] = $recChg;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addCost($recCost)
    {
        $this->recCost[] = $recCost;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addRef($recRefs)
    {
        $this->recRefs[] = $recRefs;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addDiary($diary)
    {
        $this->diary[] = $diary;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addContainer($recCont)
    {
        $this->recCont[] = $recCont;
    }

    /**
     * Add Address to object.
     *
     * @param type $docAdds
     */
    public function addDims($jobDims)
    {
        $this->jobDims[] = $jobDims;
    }

    /**
     * Returns XML for an object or array of objects.
     *
     * @param type $tableName
     * @return type
     */
    public function buildXML($tableName)
    {
        $xml = '';

        if (isset($this->$tableName) && $this->$tableName) {
            switch ($tableName) {
                case 'recCont':
                case 'jobDims':
                case 'docAdds':
                case 'recChg':
                case 'recCost':
                case 'recRefs':
                case 'recDate':
                case 'diary':

                    foreach ($this->$tableName as $table) {
                        $xml .= $table->toXML();
                    }
                    break;

                default:
                    $xml .= $this->$tableName->toXML();
                    break;
            }
        }

        return $xml;
    }

    /**
     * Create Objects for the following tables :-
     * jobHdr, jobLine, jobCol, jobDel, recCont, jobDims,
     * docAdds, recChg, recCost,recRefs, recDate, diary.
     *
     * @param type string tableName
     * @return table object
     */
    public function create($tableName)
    {
        switch (strtolower($tableName)) {

            case 'jobhdr':
                return new JobHdr();
                break;

            case 'jobline':
                return new JobLine();
                break;

            case 'jobcol':
                return new JobCol();
                break;

            case 'jobdel':
                return new JobDel();
                break;

            case 'reccont':
                return new RecCont();
                break;

            case 'jobdims':
                return new JobDims();
                break;

            case 'docadds':
                return new DocAdds();
                break;

            case 'recchg':
                return new RecChg();
                break;

            case 'reccost':
                return new RecCost();
                break;

            case 'recrefs':
                return new RecRefs();
                break;

            case 'recdate':
                return new RecDate();
                break;

            case 'diary':
                return new Diary();
                break;

            default:
                break;
        }
    }
}
