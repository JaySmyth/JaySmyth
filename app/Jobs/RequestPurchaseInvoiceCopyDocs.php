<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RequestPurchaseInvoiceCopyDocs implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    /*
     * Invoices that require copy docs.
     */

    protected $invoices;

    /*
     * User requesting copy docs.
     */
    protected $user;

    /*
     * FedEx contact to email copy docs requests to.
     */
    protected $fedexContact;

    /*
     * UPS contact to email copy docs requests to.
     */
    protected $upsContact;

    /*
     * IFS reply address.
     */
    protected $importsDistributionList;

    /*
     * IFS reply address.
     */
    protected $exportsDistributionList;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoices, User $user)
    {
        $this->invoices = $invoices;

        $this->user = $user;

        $this->fedexContact = 'globaluk@fedex.com';

        $this->upsContact = false; // Not currently sending UPS copy docs requests

        $this->importsDistributionList = 'courierinvoice_imports@antrim.ifsgroup.com';

        $this->exportsDistributionList = 'courierinvoice_exports@antrim.ifsgroup.com';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendFedexEmails();
        //$this->sendUpsEmails();
    }

    /**
     * Send FedEx email.
     *
     * @return bool
     */
    private function sendFedexEmails()
    {
        $fedexImportInvoices = $this->invoices->where('carrier_id', 2)->where('import_export', 'I');

        $fedexExportInvoices = $this->invoices->where('carrier_id', 2)->where('import_export', 'E');

        if ($fedexImportInvoices->count() > 0) {
            Mail::to($this->fedexContact)->cc($this->user->email)->send(new \App\Mail\CopyDocs($fedexImportInvoices, $this->importsDistributionList, 'Copy Docs Request (Imports)'));
        }

        if ($fedexExportInvoices->count() > 0) {
            Mail::to($this->fedexContact)->cc($this->user->email)->send(new \App\Mail\CopyDocs($fedexExportInvoices, $this->exportsDistributionList, 'Copy Docs Request (Exports)'));
        }
    }

    /**
     * Send UPS email.
     *
     * @return bool
     */
    private function sendUpsEmails()
    {
        $upsInvoices = $this->invoices->where('carrier_id', 3);

        if ($upsInvoices->count() > 0) {
            Mail::to($this->upsContact)->cc($this->user->email)->send(new \App\Mail\CopyDocs($upsInvoices));
        }
    }
}
