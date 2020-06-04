<?php

namespace App\Jobs;

use App\Models\InvoiceRun;
use App\Models\Shipment;
use App\Models\User;
use App\ScsXml\ISLEDI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class GenerateMultifreightSalesXml implements ShouldQueue
{
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
        $this->shipments = $shipments;
        $this->department_id = (! $department_id) ? 'All' : $department_id;
        $this->user = $user;
        $this->createInvoiceRun();
    }

    /**
     * Create an invoice run record.
     */
    private function createInvoiceRun()
    {
        $this->invoiceRun = InvoiceRun::create([
            'department_id' => $this->department_id,
            'user_id' => $this->user->id,
            'status' => 'Processing',
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->shipments)) {
            return true;
        }

        if (isset($this->invoiceRun)) {

            // Generate XML
            $edi = new ISLEDI($this->user->email);
            $xml = $edi->createXMLSalesInvoice($this->shipments);

            // Write XML file to directory for transfer to and processing by SCS
            $filename = date('ymdHis');

            if (Storage::disk('salesinvoices')->put($filename.'.tmp', $xml)) {

                // Rename the temp file
                Storage::disk('salesinvoices')->move($filename.'.tmp', $filename.'.xml');

                // Update the invoicing status of the shipments after the XML has been created successfully
                Shipment::whereIn('id', $this->shipments)->update([
                    'invoicing_status' => 1,
                    'invoice_run_id' => $this->invoiceRun->id,
                ]);

                // Change status of this Invoice run to "Success"
                $this->invoiceRun->status = 'Success';
                $this->invoiceRun->save();
            }
        }
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed($exception)
    {
        // Firstly send an email to raise the alarm
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed('Generate MultiFreight Sales XML', $exception));

        // Now try to set status of Invoice run to Failed
        $this->invoiceRun->status = 'Failed';
        $this->invoiceRun->save();
    }
}
