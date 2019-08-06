<?php

namespace App\Console\Commands;

use App\Multifreight\JobHdr;
use App\PurchaseInvoice;
use App\Shipment;
use Illuminate\Console\Command;

class UpdateScsJobNumbersOnPurchaseInvoiceLines extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:update-scs-job-numbers-on-purchase-invoice-lines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update scs job numbers on purchase invoice lines';

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
        $purchaseInvoices = PurchaseInvoice::whereStatus(0)->get();

        foreach ($purchaseInvoices as $purchaseInvoice) {

            $this->line('Updating SCS job numbers on invoice with ID: ' . $purchaseInvoice->id);

            foreach ($purchaseInvoice->lines as $line) {

                $this->info('Carrier tracking number:' .  $line->carrier_tracking_number);

                if (!$line->scs_job_number && $line->carrier_tracking_number) {

                    // If linked to a shipment record, check it for SCS job number first             
                    if ($line->shipment && $line->shipment->scs_job_number) {

                        $line->scs_job_number = $line->shipment->scs_job_number;
                        $line->save();

                        $this->line('Updated using SCS job number on shipment record');

                        continue;
                    }

                    // Check Shipment for SCS job number
                    $consignment = Shipment::where('carrier_consignment_number',
                        $line->carrier_tracking_number)->orderBy('id', 'desc')->first();
                    if ($consignment) {
                        $line->scs_job_number = $consignment->scs_job_number;
                        $line->save();

                        $this->line('Updated using Shipment job number');

                        continue;
                    }

                    // Check job_header for SCS job number
                    $withHypen = substr($line->carrier_tracking_number, 0, 3).'-'.substr($line->carrier_tracking_number,
                            3);

                    $jobHdr = JobHdr::select('job_disp')
                        ->orWhere('hawb_char', $line->carrier_tracking_number)
                        ->orWhere('hawb_char', $withHypen)
                        ->orWhere('mawb_char', $line->carrier_tracking_number)
                        ->orWhere('mawb_char', $withHypen)
                        ->first();

                    if ($jobHdr) {
                        $line->scs_job_number = $jobHdr->job_disp;
                        $line->save();

                        $this->line('Updated using SCS job number JobHdr');
                    }
                }
            }

            // Check every line of the invoice and set to passed if there are no overcharges.
            $purchaseInvoice->autoPass();
        }
    }

}
