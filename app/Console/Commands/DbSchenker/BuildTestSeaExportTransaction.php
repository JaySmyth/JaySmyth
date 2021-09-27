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
        $errors = [];
        $bols = $this->getBols($test);
        foreach ($bols as $bol) {
            $jobs = JobHdr::where('bill_of_lading', $bol)->get();
            if ($jobs->count()==1) {
                $jobHdr = $jobs->first();
                $isSchenker = $this->checkIsSchenker($jobHdr);
                if ($isSchenker) {
                    $errors = $this->processJob($jobHdr);
                    if ($errors == []) {
                        $jobHdr->update(['edi_sent' => 1]);
                    }
                } else {
                    // If Non Schenker and entry_date is more than 3 days old mark as sent.
                    $closeDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +3 days'));
                    if ($jobHdr->entry_date < $closeDate) {
                        $jobHdr->edi_sent = 1;
                        $jobHdr->save();
                    }
                    // No need to notify result
                    continue;
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

    protected function checkIsSchenker($jobHdr)
    {
        $isSchenker = false;
        $addressTypes = ['CONSEE', 'FOREIGN', 'NOTIFY'];
        $addresses = $jobHdr->addresses;
        foreach ($addresses as $address) {
            // Only process some addresses
            if (in_array($address->address_type, $addressTypes)) {
                $name  = $address->name;
                $branchFound = stripos($name, 'schenker');
                // Check is this a schenker branch
                if ($branchFound !== false) {
                    $isSchenker = true;
                    continue;
                }
            }
        }

        return $isSchenker;
    }

    protected function processJob($jobHdr)
    {
        $oceanMsg = new OceanMsg();
        $dbSchenker = new DbSchenker();
        $errors = $oceanMsg->buildMsg($jobHdr);
        if ($errors == []) {
            $errors = $oceanMsg->validateMsg();
            $this->msg = json_encode($oceanMsg->msg);
            dd(json_encode($this->msg));
            if ($errors == []) {
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
            $bols = JobHdr::wherein('bill_of_lading', ['IFFSXJ00012352'])
                ->groupBy('bill_of_lading')
                ->pluck('bill_of_lading')
                ->toArray();
        } else {
            // Get all BOLs addressed not yet processed (not just Schenker)
            $bols = JobHdr::where('job_date', '>=', '2021-06-01')
                ->where('job_dept', 'IFFSX')
                ->where('edi_sent', '0')
                ->where('entry_number', '>', '')
                ->groupBy('bill_of_lading')
                ->pluck('bill_of_lading')
                ->toArray();
        }

        return $bols;
    }
}
