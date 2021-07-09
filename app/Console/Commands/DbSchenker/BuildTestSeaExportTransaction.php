<?php

namespace App\Console\Commands\DbSchenker;

use App\Multifreight\JobHdr;
use App\Multifreight\JobLine;
// use App\Multifreight\JobCol;
// use App\Multifreight\JobDel;
use App\Multifreight\DocAdds;
use App\Multifreight\RecCont;
use App\Models\Unlocode;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BuildTestSeaExportTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:build-test-sea-export-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a sample SeaFreight Export Transaction for DBSchenker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $test = true;

        $bols = $this->getBols($test);

        foreach ($bols as $bol) {
            $msg = $this->buildMsg($bol);
        }
        dd(json_encode($msg));

        $this->info('Finished');
    }

    protected function getBols($test)
    {
        if ($test) {
            $bols = JobHdr::wherein('bill_of_lading', ['IFFSXJ00012331'])
                ->groupBy('bill_of_lading')
                ->pluck('bill_of_lading')
                ->toArray();
        } else {
            // Get all Schenker agent addresses
            $schenker = DocAdds::select('address_code')
                ->where('address_type', 'FOREIGN')
                ->where('name', 'like', '%Schenker%')
                ->groupBy('address_code')
                ->pluck('address_code')
                ->toArray();
            // Get all BOLs fo rSchenker addressed not yet processed
            $bols = JobHdr::where('job_date', '>=', '2021-06-01')
                ->where('job_dept', 'IFFSX')
                ->where('edi_sent', '0')
                ->whereIn('address_code', $schenker)
                ->groupBy('bill_of_lading')
                ->pluck('bill_of_lading')
                ->toArray();
        }

        return $bols;
    }

    /**
     * Sets the file type and table.
     *
     * @param type $filename
     * @return bool
     */
    protected function buildMsg($bol)
    {
        $msg = [];
        $jobs = JobHdr::where('bill_of_lading', $bol)->get();
        if ($jobs->count()==1) {
            $jobHdr = $jobs->first();
            $jobLines = JobLine::where('job_id', $jobHdr->job_id)->get();
            if ($jobLines) {
                $containers = RecCont::where('rec_id', $jobHdr->job_id)->orderBy('line_no')->get();
                $consor = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSOR')->where('line_no', '1')->first();
                $consee = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSEE')->where('line_no', '1')->first();
                $notify = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'NOTIFY')->where('line_no', '1')->first();
                $carrier = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CARRIER')->where('line_no', '1')->first();
            }
        } else {
            dd("Unable to process BOL: $bol "). $jobs->count() . " Entries";
        }

        // Admin Record
        $msg['ADMIN'] = $this->buildAdmin($jobHdr, $jobLines, $notify->keyname, $msg);

        // Header Record
        $msg['HEADER'] = $this->buildHeader($jobHdr->bill_of_lading, $jobHdr->bol_orig);

        // Reference records
        $msg['REFERENCE'] = $this->buildReferences($jobHdr);

        // Transport Header Record
        $msg['THEADER'] = $this->buildTheader($jobHdr, $carrier);

        // Transport Dates record
        $rec = $this->buildTransportDate('132', $jobHdr->estimated_arrival_date);
        if ($rec > '') {
            $msg['TDATES']['TDATE'][] = $rec;
        }
        $rec = $this->buildTransportDate('133', $jobHdr->estimated_dept_date);
        if ($rec > '') {
            $msg['TDATES']['TDATE'][] = $rec;
        }
        $rec = $this->buildTransportDate('136', $jobHdr->actual_dept_date);
        if ($rec > '') {
            $msg['TDATES']['TDATE'][] = $rec;
        }
        if ($jobHdr->port_of_loading > '') {
            $unlocode = new Unlocode();
            $locode = $unlocode->getLoCode($consor->country_code, $jobHdr->port_of_loading, $jobHdr->pol_description);
            $rec = $this->buildTransportLocns('5', $locode);
            if ($rec != '') {
                $msg['TLOCATIONS']['TLOCATION'][] = $rec;
            }
        }
        /*
        if ($jobHdr->pol_description > '') {
            $rec = $this->buildTransportLocns('9', 'GB' . $jobHdr->pol_description);
            if ($rec != '') {
                $msg['TLOCATIONS']['TLOCATION'][] = $rec;
            }
        }
        */
        if ($jobHdr->final_dest > '' || $jobHdr->final_desc > '') {
            $unlocode = new Unlocode();
            $locode = $unlocode->getLoCode($consee->country_code, $jobHdr->final_dest, $jobHdr->final_desc);
            $rec = $this->buildTransportLocns('7', $locode);
            if ($rec != '') {
                $msg['TLOCATIONS']['TLOCATION'][] = $rec;
            }
        }
        if ($jobHdr->port_of_discharge > '' || $jobHdr->pod_description > '') {
            $unlocode = new Unlocode();
            $locode = $unlocode->getLoCode($consee->country_code, $jobHdr->port_of_discharge, $jobHdr->pod_description);
            $rec = $this->buildTransportLocns('12', $locode);
            if ($rec != '') {
                $msg['TLOCATIONS']['TLOCATION'][] = $rec;
            }
        }
        // Address Records
        $rec = $this->buildAddress('CN', $consee);
        if ($rec != '') {
            $msg['TADDRESS']['PARTNERADDR'][] = $rec;
        }
        $rec = $this->buildAddress('CZ', $consor);
        if ($rec != '') {
            $msg['TADDRESS']['PARTNERADDR'][] = $rec;
        }
        $rec = $this->buildAddress('BA', $consee);
        if ($rec != '') {
            $msg['TADDRESS']['PARTNERADDR'][] = $rec;
        }

        // Communications Contact Record - Ignored

        // Goods Description records
        foreach ($jobLines as $jobLine) {
            $rec = $this->buildLine($jobLine);
            if ($rec != '') {
                $msg['GOODSDETAILS']['GOODS'][] = $rec;
            }
        }

        // Package Details record
        $msg['PACKAGE'] = $this->buildPackage($jobHdr);

        // Equipment Unit record
        // if (strtoupper($jobHdr->load_type) == 'FCL') {
        $msg['EQUIPMENTUNIT'] =  $this->buildEquipmentUnit($jobHdr, $containers);
        //}

        // Delivery Terms record
        $msg['DTERMS'] = $this->buildDterms($jobHdr->terms_code, $jobHdr->terms_location);

        return $msg;
    }

    protected function natureOfGoods($jobLines)
    {
        $nature = 'GEN';
        foreach ($jobLines as $jobLine) {
            if ($jobLine->haz_pieces > 0) {
                $nature = 'HAZ';
            }
        }
        return $nature;
    }

    protected function getSTTNumber()
    {
        $countryCode = '826';
        $partnerId = '6';
        $sequence = nextAvailable('SCHENKERSTT');
        $base = $countryCode.$partnerId.sprintf('%9d', $sequence);
        $sum1 = $base[1]+$base[3]+$base[5]+$base[7]+$base[9]+$base[11];
        $sum2 = $base[0]+$base[2]+$base[4]+$base[6]+$base[8]+$base[10]+$base[12];
        $sum = $sum1 + ($sum2*3);
        $checkDigit = 10 - $sum % 10;

        return $base.$checkDigit;
    }

    protected function buildReference($qualifier = 'AEG', $custRef = '')
    {
        if ($custRef > "") {
            return [
                'REFERENCE-QUALIFIER' => $qualifier,
                'REFERENCE-NUMBER' => $custRef
            ];
        }

        return '';
    }

    protected function getCarrierSCACCode($carrier)
    {
        return $carrier->reference;
    }

    protected function buildTransportDate($qualifier, $date)
    {
        /*
            132 - Est Date of Arrival
            133 - Est Date of Departure
            136 - Departure Date
        */
        if ($date > "") {
            return [
                'TRANSPORT-QUALIFIER' => $qualifier,
                'TRANSPORT-DATE' => date('Ymd', strtotime($date)),
                'TRANSPORT-DATE-FORMAT' => '102',
            ];
        }

        return '';
    }

    protected function buildTransportLocns($qualifier, $locn)
    {
        // Transport Location record
        /*
            5 = Port of Loading
            7 = Place of Delivery (Final Destination)
            9 = Place of Loading
            12 = Port of Discharge
        */
        if ($locn > "") {
            return [
                'PLACE-QUALIFIER' => $qualifier,
                'PLACE-ID' => $locn,
            ];
        }

        return '';
    }

    protected function buildAddress($qualifier, $address = '')
    {
        $data = '';
        if ($address) {
            switch ($qualifier) {
                case 'BA':
                    $data = [
                        'ADDRESS-QUALIFIER' => 'CN',
                        'ADDRESS-ID' => $address->id,
                        'ADDRESS-STRUCTURE' => '2',
                        'NAME' => $address->name ?? '',
                        'STREET-AND-NUMBER1' => $address->address_1 ?? '',
                        'CITY-NAME' => $address->town ?? '',
                        'POST-CODE' => $address->postcode ?? '',
                        'COUNTRY-CODE' => $address->country_code ?? '',
                        'COM-ADDRESS-ID' => $qualifier ?? '',
                        'CONTACT-FUNCTION' => 'IC',
                        'CONTACT-EMPLOYEE-NAME' => $address->contact_name ?? '',
                        'COMMUNICATION-ID' => 'TE',
                        'COMMUNICATION-DATA' => $address->telephone ?? '',
                    ];
                    break;

                case 'CN':
                case 'CZ':
                    $data = [
                        'ADDRESS-QUALIFIER' => $qualifier,
                        'ADDRESS-ID' => $address->id,
                        'ADDRESS-STRUCTURE' => '2',
                        'NAME' => $address->name ?? '',
                        'STREET-AND-NUMBER1' => $address->address_1 ?? '',
                        'CITY-NAME' => $address->town ?? '',
                        'POST-CODE' => $address->postcode ?? '',
                        'COUNTRY-CODE' => $address->country_code ?? '',
                        'COM-ADDRESS-ID' => $qualifier ?? '',
                        'CONTACT-FUNCTION' => 'IC',
                        'CONTACT-EMPLOYEE-NAME' => $address->contact_name ?? '',
                        'COMMUNICATION-ID' => 'TE',
                        'COMMUNICATION-DATA' => $address->telephone ?? '',
                    ];
                    break;

                    default:
                        $data = '';
                        break;
            }
        }

        return $data;
    }

    protected function buildAdmin($jobHdr, $jobLines, $keyName)
    {
        $msg['ORIGIN-BRANCH-ID'] = 'SJCBFS';
        $msg['BL-MESSAGE-ID'] = $jobHdr->job_disp;
        $msg['HOUSE-BL-RUNNING-NUMBER'] = 1;
        $msg['STT-NUMBER'] = $this->getSTTNumber();
        $msg['EXPORT / IMPORT'] = 'E';
        $msg['ETD-DATE'] = date('Ymd', strtotime($jobHdr->estimated_dept_date));
        $msg['ETD-TIME'] = '0000';
        $msg['TIME-ZONE'] = 'GMT';
        $msg['ETA-DATE'] = date('Ymd', strtotime($jobHdr->delivered_date));
        $msg['ETA-TIME'] = '0000';
        $msg['CR-DATE'] = date('Ymd');
        $msg['CR-TIME'] = date('His');
        $msg['HOUSE-BL-USING-CODE'] = 'O';
        $msg['NATURE-OF-GOODS'] = $this->natureOfGoods($jobLines);
        $msg['FINAL-DESTINATION-BRANCH'] = $keyName.'SSF';
        $msg['STATUS'] = 'BL1.4.1';
        $msg['LOAD-TYPE'] = $jobHdr->load_type;

        return $msg;
    }

    protected function buildHeader($bolNumber, $bolCopies)
    {
        $msg['DOCUMENT-CODE'] = '703';
        $msg['MESSAGE-ID'] = $bolNumber;
        $msg['MESSAGE-FUNCTION'] = '9';       // 9 -  Create, 5 - Replace (Update)
        $msg['DOCUMENT-PLACE'] = 'GBANT';     //  GBBEL $jobHdr->receipt;
        $msg['DOCUMENT-DATE'] = date('Ymd');
        $msg['DOCUMENT-DATE-FORMAT'] = '102';
        $msg['NUMBER-OF-ORIGINALS'] = intval($bolCopies);
        $msg['BL-NUMBER'] = $bolNumber;

        return $msg;
    }

    protected function buildReferences($jobHdr)
    {
        $msg = [];
        $ref = $this->buildReference('AEG', $jobHdr->cust_ref);
        if ($ref > '') {
            $msg['REFERENCE'][] = $ref;
        }

        $ref = $this->buildReference('FF', $jobHdr->job_disp);
        if ($ref > '') {
            $msg['REFERENCE'][] = $ref;
        }

        return $msg;
    }

    protected function buildTheader($jobHdr, $carrier)
    {
        $msg['TRANSPORT-STAGE-QUALIFIER'] = '20';
        $msg['CONVEYANCE-REFERENCE'] = $jobHdr->vessel_code;
        $msg['MODE-OF-TRANSPORT'] = '10';
        $msg['TYPE-OF-TRANSPORT'] = '13';
        $msg['CARRIER-ID'] = $this->getCarrierSCACCode($carrier);
        $msg['CARRIER-NAME'] = $carrier->name;
        $msg['TRANSPORT-ID'] = $jobHdr->vessel_name;

        return $msg;
    }

    protected function buildLine($jobLine)
    {
        $line = [
            'ITEM-NUMBER' => $jobLine->line_no,
            'NUMBER-OF-PACKAGES' => $jobLine->pieces,
            'MEASUREMENT' => null,
        ];

        // Add Actual Weight
        $measurement[] = [
                    "MEASURE-QUALIFIER" => "WT",
                    'MEASURE-UNIT' => 'KGM',
                    'MEASURE-VALUE' => $jobLine->entered_wgt,
        ];

        // Add Volumetric Weight
        $measurement[] = [
                    "MEASURE-QUALIFIER" => "WT",
                    'MEASURE-UNIT' => 'MTQ',
                    'MEASURE-VALUE' => $jobLine->vol_wgt,
        ];

        $line['MEASUREMENT']['MEASURE'] = $measurement;

        return $line;
    }

    protected function buildPackage($jobHdr)
    {
        return [
            'TOTAL-NUMBER-PACKAGES' => $jobHdr->pieces,
            'TOTAL-GROSS-WEIGHT' => $jobHdr->chg_weight,
            'WEIGHT-MEASURE-QUALIFIER' => 'KGM',
            'TOTAL-CUBE' => $jobHdr->cube,
            'CUBE-MEASURE-QUALIFIER' => 'MTQ',
        ];
    }

    protected function buildEquipmentUnit($jobHdr, $containers)
    {
        $equipUnit = [];
        foreach ($containers as $container) {
            $data = [
                'EQUIPMENT-QUALIFIER' => 'CN',
                'EQUIPMENT-ID-NUMBER' => $container->container_number,
                'SIZE-AND-TYPE-TEXT' => $container->container_code."' Container",
                'SUPPLIER' => '2',
                'MOVEMENT-PLAN' => $jobHdr->load_type.'/'.$jobHdr->load_type,
                'MEASUREMENT' => null,
                'SEAL' => $container->seal_number,

            ];
            $data['MEASUREMENT'] = $this->buildContainerMeasurement($jobHdr);

            $equipUnit[] = $data;
        }

        return $equipUnit;
    }

    protected function buildContainerMeasurement($container)
    {
        $data['MEASURE'][] = [
            "MEASURE-QUALIFIER" => "WT",
            "MEASURE-DIMENSION-CODE" => "U",
            "MEASURE-UNIT" => "KGM",
            "MEASURE-VALUE" => $container->kgs_weight,

        ];
        $data['MEASURE'][] = [
            "MEASURE-QUALIFIER" => "VOL",
            "MEASURE-DIMENSION-CODE" => "U",
            "MEASURE-UNIT" => "MTQ",
            "MEASURE-VALUE" => $container->cube,
        ];

        return $data;
    }

    protected function buildDterms($terms, $locn)
    {
        $msg['TERMS-OF-DELIVERY-CODE'] = $terms;
        $msg['TERMS-OF-DELIVERY-CODE-LIST'] = $terms;
        $msg['TERMS-OF-DELIVERY-TEXT-1'] = $terms.' '.$locn;

        return $msg;
    }
}
