<?php

namespace App\Jobs;

use App\User;
use App\Shipment;
use App\InvoiceRun;
use App\ScsXml\ISLEDI;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class GenerateMultifreightSalesXml implements ShouldQueue {

    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $shipments;
    protected $department_id;
    protected $user;
    protected $invoiceRun;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shipments, $department_id, User $user)
    {
        $this->shipments = Shipment::findMany($shipments);
        $this->department_id = (!$department_id) ? 'All' : $department_id;
        $this->user = $user;
        $this->createInvoiceRun();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->shipments->count() == 0) {
            return true;
        }

        if (isset($this->invoiceRun)) {

            // Generate XML
            $edi = new ISLEDI($this->user->email);
            $xml = $edi->createXMLSalesInvoice($this->shipments, $this->invoiceRun);

            // Write XML file to directory for transfer to and processing by SCS
            $filename = date('ymdHis');

            if (Storage::disk('salesinvoices')->put($filename . ".tmp", $xml)) {
                Storage::disk('salesinvoices')->move($filename . '.tmp', $filename . '.xml');

                // Change status of this Invoice run to "Success"
                $this->invoiceRun->status = "Success";
                $this->invoiceRun->save();
            }
        }
    }

    /**
     * Create an invoice run record.
     */
    private function createInvoiceRun()
    {
        $this->invoiceRun = InvoiceRun::create([
                    'department_id' => $this->department_id,
                    'user_id' => $this->user->id,
                    'status' => 'Processing'
        ]);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {

        // Firstly send an email to raise the alarm
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed("Generate MultiFreight Sales XML", $exception));

        // Now try to set status of Invoice run to Failed
        $this->invoiceRun->status = "Failed";
        $this->invoiceRun->save();
    }

}
