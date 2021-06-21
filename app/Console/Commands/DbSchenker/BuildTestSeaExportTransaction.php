<?php

namespace App\Console\Commands\DbSchenker;

use App\Multifreight\JobHdr;
use App\Multifreight\JobLine;
// use App\Multifreight\JobCol;
// use App\Multifreight\JobDel;
use App\Multifreight\DocAdds;

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
        $jobs = ['IFFSXJ00012331'];

        foreach ($jobs as $job) {
            $jobHdr = JobHdr::where('job_disp', $job)->first();
            if ($jobHdr) {
                $jobLines = JobLine::where('job_id', $jobHdr->job_id)->get();
                if ($jobLines) {
                    // $jobCol = JobCol::where('job_id', $jobHdr->job_id)->first();
                    // $jobDel = JobDel::where('job_id', $jobHdr->job_id)->first();
                    $consor = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSOR')->where('line_no', '1')->first();
                    $consee = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSEE')->where('line_no', '1')->first();
                    $notify = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'NOTIFY')->where('line_no', '1')->first();
                    $carrier = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CARRIER')->where('line_no', '1')->first();

                    $msg = $this->buildMsg($jobHdr, $jobLines, $consor, $consee, $notify, $carrier);
                }
            }
            dd(json_encode($msg));
        }

        $this->info('Finished');
    }

    /**
     * Sets the file type and table.
     *
     * @param type $filename
     * @return bool
     */
    protected function buildMsg($jobHdr, $jobLines, $consor = null, $consee = null, $notify = null, $carrier = null)
    {
        $msg = [];
        $msg['ADMIN']['ORIGIN-BRANCH-ID'] = 'GB9000SSF';
        $msg['ADMIN']['BL-MESSAGE-ID'] = $jobHdr->cust_ref;
        $msg['ADMIN']['HOUSE-BL-RUNNING-NUMBER'] = $jobHdr->bol_number; // *** - 1
        $msg['ADMIN']['STT-NUMBER'] = $this->getSTTNumber();
        $msg['ADMIN']['EXPORT / IMPORT'] = 'E';
        $msg['ADMIN']['ETD-DATE'] = date('Ymd', strtotime($jobHdr->estimated_dept_date));
        $msg['ADMIN']['ETD-TIME'] = '0000';
        $msg['ADMIN']['TIME-ZONE'] = 'GMT';
        $msg['ADMIN']['ETA-DATE'] = date('Ymd', strtotime($jobHdr->delivered_date));
        $msg['ADMIN']['ETA-TIME'] = '0000';
        $msg['ADMIN']['CR-DATE'] = date('Ymd');
        $msg['ADMIN']['CR-TIME'] = date('His');
        $msg['ADMIN']['HOUSE-BL-USING-CODE'] = 'O';
        $msg['ADMIN']['NATURE-OF-GOODS'] = $this->natureOfGoods($jobLines);
        $msg['ADMIN']['FINAL-DESTINATION-BRANCH'] = $jobHdr->final_dest; // ***
        $msg['ADMIN']['STATUS'] = 'BL1.4.1';
        $msg['ADMIN']['LOAD-TYPE'] = $jobHdr->load_type;

        // Header Record
        $msg['HEADER']['DOCUMENT-CODE'] = '703';
        $msg['HEADER']['MESSAGE-ID'] = $jobHdr->bol_number;
        $msg['HEADER']['MESSAGE-FUNCTION'] = '9';
        $msg['HEADER']['DOCUMENT-PLACE'] = $jobHdr->receipt;
        $msg['HEADER']['DOCUMENT-DATE'] = date('Ymd');
        $msg['HEADER']['DOCUMENT-DATE-FORMAT'] = '102';
        $msg['HEADER']['NUMBER-OF-ORIGINALS'] = $jobHdr->bol_orig;
        $msg['HEADER']['BL-NUMBER'] = $jobHdr->bol_number;

        // Reference records
        $ref = $this->buildReference('AEG', $jobHdr->cust_ref);
        if ($ref > '') {
            $msg['REFERENCES']['REFERENCE'][] = $ref;
        }

        $ref = $this->buildReference('FF', $jobHdr->job_disp);
        if ($ref > '') {
            $msg['REFERENCES']['REFERENCE'][] = $ref;
        }


        // Transport Header Record
        $msg['THEADER']['TRANSPORT-STAGE-QUALIFIER'] = '20';
        $msg['THEADER']['CONVEYANCE-REFERENCE'] = $jobHdr->vessel_name;
        $msg['THEADER']['MODE-OF-TRANSPORT'] = '10';
        $msg['THEADER']['TYPE-OF-TRANSPORT'] = '13';
        $msg['THEADER']['CARRIER-ID'] = $this->getCarrierSCACCode($carrier);
        $msg['THEADER']['CARRIER-NAME'] = $carrier->name;
        $msg['THEADER']['TRANSPORT-ID'] = $jobHdr->vessel_name;

        // Transport Dates record
        /*
            132 - Est Date of Arrival
            133 - Est Date of Departure
            136 - Departure Date
        */
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

        // Transport Location record
        /*
            5 = Port of Loading
            7 = Place of Delivery (Final Destination)
            9 = Place of Loading
            12 = Port of Discharge
        */
        $rec = $this->buildTransportLocns('5', $jobHdr->port_of_loading);
        if ($rec != '') {
            $msg['TLOCATIONS']['TLOCATION'][] = $rec;
        }
        $rec = $this->buildTransportLocns('7', $jobHdr->final_dest);
        if ($rec != '') {
            $msg['TLOCATIONS']['TLOCATION'][] = $rec;
        }
        $rec = $this->buildTransportLocns('9', $jobHdr->port_of_loading);
        if ($rec != '') {
            $msg['TLOCATIONS']['TLOCATION'][] = $rec;
        }
        $rec = $this->buildTransportLocns('12', $jobHdr->port_of_discharge);
        if ($rec != '') {
            $msg['TLOCATIONS']['TLOCATION'][] = $rec;
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
        $msg['EQUIPMENTUNIT'] =  $this->buildEquipmentUnit($jobHdr);

        // Delivery Terms record
        $msg['DTERMS']['TERMS-OF-DELIVERY-CODE'] = $jobHdr->terms_code;
        $msg['DTERMS']['TERMS-OF-DELIVERY-CODE-LIST'] = '?';
        $msg['DTERMS']['TERMS-OF-DELIVERY-TEXT-1'] = '?';


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
            'TOTAL-GROSS-WEIGHT' => $jobHdr->chg_wgt,
            'WEIGHT-MEASURE-QUALIFIER' => 'KGM',
            'TOTAL-CUBE' => $jobHdr->cube,
            'CUBE-MEASURE-QUALIFIER' => 'MTQ',
        ];
    }

    protected function buildEquipmentUnit($jobHdr)
    {
        $data = [
            'EQUIPMENT-QUALIFIER' => 'CN',
            'EQUIPMENT-ID-NUMBER' => $jobHdr->marks ?? '',
            'SIZE-AND-TYPE-TEXT' => $jobHdr->package_type,
            'SUPPLIER' => '2',
            'MOVEMENT-PLAN' => $jobHdr->load_type.'/'.$jobHdr->load_type,
            'MEASUREMENT' => null
        ];
        $data['MEASUREMENT'] = $this->buildContainerMeasurement($jobHdr);

        return $data;
    }

    protected function buildContainerMeasurement($jobHdr)
    {
        $data['MEASURE'][] = [
            "MEASURE-QUALIFIER" => "WT",
            "MEASURE-DIMENSION-CODE" => "U",
            "MEASURE-UNIT" => "KGM",
            "MEASURE-VALUE" => $jobHdr->kgs_weight,
        ];
        $data['MEASURE'][] = [
            "MEASURE-QUALIFIER" => "VOL",
            "MEASURE-DIMENSION-CODE" => "U",
            "MEASURE-UNIT" => "KGM",
            "MEASURE-VALUE" => $jobHdr->vol_weight,
        ];

        return $data;
    }
}
