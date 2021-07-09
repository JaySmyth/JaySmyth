<?php

namespace App\Console\Commands\DbSchenker;

use App\Multifreight\JobHdr;
use App\Multifreight\DocAdds;
use App\DBSchenker\OceanMsg;
use App\DBSchenker\DbSchenker;

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
    protected $msg;

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
            $jobs = JobHdr::where('bill_of_lading', $bol)->get();
            if ($jobs->count()==1) {
                $jobHdr = $jobs->first();
                $errors = $this->processJob($jobHdr);
                if ($errors == []) {
                    $jobHdr->update(['edi_sent' => 1]);
                }
            } else {
                return [
                    'errors' => [
                        'billOfLading' => 'Unable to process multiple jobs on one BOL. Use Procars etc.'
                    ]
                ];
            }

            $this->notifyResult($jobHdr->job_disp, $errors);
        }

        $this->info('Finished');
    }

    protected function processJob($jobHdr)
    {
        $oceanMsg = new OceanMsg();
        $dbSchenker = new DbSchenker();
        $errors = $oceanMsg->buildMsg($jobHdr);
        if ($errors == []) {
            $errors = $oceanMsg->validateMsg();
            if ($errors == []) {
                $this->msg = json_encode($oceanMsg->msg);
                $result = $dbSchenker->sendMessage(json_encode($this->msg));
                $errors = $result['errors'];
            }
        }

        return $errors;
    }

    protected function notifyResult($jobRef, $errors = [], $msg = '')
    {
        $msg = [];
        $warning = '';
        if ($errors == []) {
            $detail = "SCS Job $jobRef successfully sent by EDI to DBSchenker";
        } else {
            $warning = "Error in EDI transmission";
            $detail = "Failed when sending $jobRef to DBSchenker - Please correct";
            foreach ($errors as $heading => $error) {
                foreach ($error as $field => $errorMsg) {
                    $msg[] = "Section: $heading - $field: $errorMsg";
                }
            }
        }
        Mail::to('renglish@antrim.ifsgroup.com')->send(new \App\Mail\GenericError(
            'DBSchenker EDI ('.$jobRef.')',
            $msg,
            false,
            $warning,
            $detail
        ));

        $msg[] = $this->msg; // Add json for debugging
        Mail::to('gmcbroom@antrim.ifsgroup.com')->send(new \App\Mail\GenericError(
            'DBSchenker EDI ('.$jobRef.')',
            $msg,
            false,
            $warning,
            $detail
        ));
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
}
