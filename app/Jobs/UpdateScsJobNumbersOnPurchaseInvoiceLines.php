<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateScsJobNumbersOnPurchaseInvoiceLines implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    /*
     * Purchase invoice to update with SCS job numbers.
     */

    protected $purchaseInvoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($purchaseInvoice)
    {
        $this->purchaseInvoice = $purchaseInvoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->purchaseInvoice->lines as $line) {
            if (! $line->scs_job_number && $line->carrier_tracking_number) {

                // If linked to a shipment record, check it for SCS job number first
                if ($line->shipment && $line->shipment->scs_job_number) {
                    $line->scs_job_number = $line->shipment->scs_job_number;
                    $line->save();
                } else {

                    // Check job header for SCS job number
                    $withHypen = substr($line->carrier_tracking_number, 0, 3).'-'.substr($line->carrier_tracking_number, 3);

                    $jobHdr = \App\Multifreight\JobHdr::select('job_disp')
                            ->orWhere('hawb_char', $line->carrier_tracking_number)
                            ->orWhere('hawb_char', $withHypen)
                            ->orWhere('mawb_char', $line->carrier_tracking_number)
                            ->orWhere('mawb_char', $withHypen)
                            ->first();

                    if ($jobHdr) {
                        $line->scs_job_number = $jobHdr->job_disp;
                        $line->save();
                    }
                }
            }
        }

        // Check every line of the invoice and set to passed if there are no overcharges.
        $this->purchaseInvoice->autoPass();
    }
}
