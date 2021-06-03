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
        $jobs = ['IFFSXJ00012337'];
        // $jobs = ['IFFSIJ00028836'];

        foreach ($jobs as $job) {
            $jobHdr = JobHdr::where('job_disp', $job)->first();
            if ($jobHdr) {
                $jobLine = JobLine::where('job_id', $jobHdr->job_id)->first();
                if ($jobLine) {
                    // $jobCol = JobCol::where('job_id', $jobHdr->job_id)->first();
                    // $jobDel = JobDel::where('job_id', $jobHdr->job_id)->first();
                    $consee = DocAdds::where('job_id', $jobHdr->job_id)->where('address_type', 'CONSEE')->where('line_no', '1')->first();

                    $msg[] = $this->buildMsg($jobHdr, $jobLine, $consee);
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
    protected function buildMsg($jobHdr, $jobLine, $consee = null)
    {
        $msg = [];
        $msg['ADMIN']['ORIGIN-BRANCH-ID'] = 'unknown';
        $msg['ADMIN']['BL-MESSAGE-ID'] = $jobHdr->cust_ref;
        $msg['ADMIN']['HOUSE-BL-RUNNING-NUMBER'] = $jobHdr->bol_number; // ***
        $msg['ADMIN']['HOUSE-BL-MESSAGE-ID'] = $jobHdr->bol_number; // ***
        $msg['ADMIN']['EXPORT / IMPORT'] = 'E';
        $msg['ADMIN']['ETD-DATE'] = $jobHdr->estimated_dept_date;
        $msg['ADMIN']['ETD-TIME'] = '0000';
        $msg['ADMIN']['TIME-ZONE'] = 'GMT';
        $msg['ADMIN']['ETA-DATE'] = $jobHdr->delivered_date;
        $msg['ADMIN']['ETA-TIME'] = '0000';
        $msg['ADMIN']['CR-DATE'] = date('Ymd');
        $msg['ADMIN']['CR-TIME'] = date('His');
        $msg['ADMIN']['HOUSE-BL-USING-CODE'] = 'O';
        $msg['ADMIN']['NATURE-OF-GOODS'] = $this->natureOfGoods($jobLine->haz_pieces);
        $msg['ADMIN']['FINAL-DESTINATION-BRANCH'] = $jobHdr->final_dest;
        $msg['ADMIN']['STATUS'] = 'BL1.4.1';
        $msg['ADMIN']['RECIPIENT-CIRCLE'] = $jobHdr->terms_code;

        // Header Record
        $msg['HEADER']['DOCUMENT-CODE'] = '703';
        $msg['HEADER']['MESSAGE-ID'] = $jobHdr->bol_number;
        $msg['HEADER']['MESSAGE-FUNCTION'] = '9';
        $msg['HEADER']['DOCUMENT-PLACE'] = $jobHdr->receipt;
        $msg['HEADER']['DOCUMENT-DATE'] = date('Ymd');
        $msg['HEADER']['DOCUMENT-DATE-FORMAT'] = '102';
        $msg['HEADER']['NUMBER-OF-ORIGINALS'] = $jobHdr->bol_orig;
        $msg['HEADER']['BL-NUMBER'] = $jobHdr->bol_number;

        // Reference Record
        $msg['REFERENCE']['REFERENCE-QUALIFIER'] = 'AEG';
        $msg['REFERENCE']['REFERENCE-NUMBER'] = $jobHdr->cust_ref;

        // Transport Header Record
        $msg['THEADER']['CONVEYANCE-REFERENCE'] = $jobHdr->vessel_name;
        $msg['THEADER']['TYPE-OF-TRANSPORT'] = '13';
        $msg['THEADER']['CARRIER-ID'] = $jobHdr->carrier_code;
        $msg['THEADER']['CARRIER-NAME'] = $jobHdr->carrier_code;
        $msg['THEADER']['TRANSPORT-ID'] = $jobHdr->vessel_name;

        // Transport Dates record
        $msg['TDATES']['TRANSPORT-QUALIFIER'] = '136';
        $msg['TDATES']['TRANSPORT-DATE'] = $jobHdr->estimated_dept_date;
        $msg['TDATES']['TRANSPORT-DATE-FORMAT'] = '102';

        // Transport Location record
        $msg['TLOCN']['PLACE-QUALIFIER'] = '5';
        $msg['TLOCN']['PLACE-ID'] = $jobHdr->port_of_loading;

        // Address Record
        $address4 = (empty($consee->county)) ? $consee->town : $consee->town.'/'.$consee->county;
        $address5 = (empty($consee->country_code)) ? $consee->postcode : $consee->postcode.'/'.$consee->country_code;
        $msg['CNEEADDR']['ADDRESS-QUALIFIER'] = 'CN';
        $msg['CNEEADDR']['ADDRESS-STRUCTURE'] = '1';
        $msg['CNEEADDR']['NAME-AND-ADDRESS-LINE-1'] = $consee->name ?? '';
        $msg['CNEEADDR']['NAME-AND-ADDRESS-LINE-2'] = $consee->address_1 ?? '';
        $msg['CNEEADDR']['NAME-AND-ADDRESS-LINE-3'] = $consee->address_2 ?? '';
        $msg['CNEEADDR']['NAME-AND-ADDRESS-LINE-4'] = $address4;
        $msg['CNEEADDR']['NAME-AND-ADDRESS-LINE-5'] = $address5;

        // Communication Contact record
        $msg['CONTACT']['ADDRESS-ID'] = '?';
        $msg['CONTACT']['CONTACT-FUNCTION'] = 'IC';
        $msg['CONTACT']['CONTACT-EMPLOYEE'] = '?';
        $msg['CONTACT']['COMMUNICATION-ID'] = 'TE';
        $msg['CONTACT']['CONTACT-EMPLOYEE'] = '?';

        // Goods Description records
        $msg['GOODS']['ITEM-NUMBER'] = $jobLine->line_no;
        $msg['GOODS']['NUMBER-OF-PACKAGES'] = $jobLine->pieces;
        $msg['GOODS']['GOODS-MEASURE-WEIGHT-UNIT'] = 'KGM';
        $msg['GOODS']['GOODS-MEASURE-WEIGHT-VALUE'] = $jobLine->entered_wgt;
        $msg['GOODS']['GOODS-MEASURE-VOLUME-UNIT'] = 'KGM';
        $msg['GOODS']['GOODS-MEASURE-VOLUME-VALUE'] = $jobLine->vol_wgt;

        // Package Details record
        $msg['PACKAGE']['TOTAL-NUMBER-PACKAGES'] = $jobHdr->pieces;
        $msg['PACKAGE']['TOTAL-GROSS-WEIGHT'] = $jobHdr->chg_wgt;
        $msg['PACKAGE']['WEIGHT-MEASURE-QUALIFIER'] = 'KGM';
        $msg['PACKAGE']['TOTAL-CUBE'] = $jobHdr->cube;
        $msg['PACKAGE']['CUBE-MEASURE-QUALIFIER'] = 'MTQ';

        // Delivery Terms record
        $msg['DTERMS']['TERMS-OF-DELIVERY-CODE'] = $jobHdr->terms_code;
        $msg['DTERMS']['TERMS-OF-DELIVERY-CODE-LIST'] = '?';
        $msg['DTERMS']['TERMS-OF-DELIVERY-TEXT-1'] = '?';


        return $msg;
    }

    protected function natureOfGoods($qty)
    {
        return ($qty>0) ? 'HAZ' : 'GEN';
    }
}
