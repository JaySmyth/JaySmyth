<?php

namespace App\Console\Commands\DbSchenker;

use App\DBSchenker\DbSchenker;
use App\DBSchenker\OceanMsg;
use App\Mail\GenericError;
use App\Multifreight\JobHdr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
     * @var
     */
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

        foreach ($this->getBols($test) as $bol) {
            $this->line("Getting job $bol");

            $jobs = JobHdr::where('bill_of_lading', $bol)->get();

            if ($jobs->count() == 1) {
                $this->info("Found job $bol");

                $jobHdr = $jobs->first();

                if ($this->checkIsSchenker($jobHdr)) {
                    $this->info("$bol is a DBschenker job");

                    $errors = $this->processJob($jobHdr);

                    if (empty($errors)) {
                        $jobHdr->update(['edi_sent' => 1]);

                        $this->info("Transaction successful");
                    }
                } else {
                    $this->line("$bol is not DBschenker job");
                    // If Non Schenker and entry_date is more than 3 days old mark as sent.
                    $closeDate = date('Y-m-d', strtotime(date('Y-m-d').' +3 days'));
                    if ($jobHdr->entry_date < $closeDate) {
                        $jobHdr->edi_sent = 1;
                        $jobHdr->save();
                    }
                    // No need to notify result
                    continue;
                }
            } else {
                $this->error("Unable to process multiple jobs on one BOL. Use Procars.");

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

    protected function getBols($test)
    {
        if ($test) {
            return JobHdr::wherein('bill_of_lading', ['IFFSXJ00012352'])
                ->groupBy('bill_of_lading')
                ->pluck('bill_of_lading')
                ->toArray();
        }

        // Get all BOLs addressed not yet processed (not just Schenker)
        return JobHdr::where('job_date', '>=', '2021-06-01')
            ->where('job_dept', 'IFFSX')
            ->where('edi_sent', false)
            ->where('entry_number', '>', '')
            ->groupBy('bill_of_lading')
            ->pluck('bill_of_lading')
            ->toArray();
    }


    protected function checkIsSchenker($jobHdr)
    {
        foreach ($jobHdr->addresses as $address) {
            $this->line('Checking address: '.$address->address_type.'/'.$address->name);

            if (in_array($address->address_type, ['CONSEE', 'FOREIGN', 'NOTIFY']) && stripos($address->name, 'schenker')) {
                return true;
            }
        }

        return false;
    }

    protected function processJob($jobHdr)
    {
        $oceanMsg = new OceanMsg();
        $dbSchenker = new DbSchenker();
        $errors = $oceanMsg->buildMsg($jobHdr);

        if (empty($errors)) {
            $errors = $oceanMsg->validateMsg();
            $this->msg = $oceanMsg->msg;

            if (empty($errors)) {
                $result = $dbSchenker->sendRequest($this->msg);
                $errors = $result['errors'];
            }
        }

        return $errors;
    }

    protected function notifyResult($jobRef, $errors = [])
    {
        $msg = [];
        $warning = '';
        if (empty($errors)) {
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

        //Mail::to('renglish@antrim.ifsgroup.com')->send(new GenericError('DBSchenker EDI ('.$jobRef.')', $msg, false, $warning, $detail));

        $msg[] = json_encode($this->msg); // Add json for debugging
        Mail::to('dshannon@antrim.ifsgroup.com')->send(new GenericError('DBSchenker EDI ('.$jobRef.')', $msg, false, $warning, $detail));
    }
}
