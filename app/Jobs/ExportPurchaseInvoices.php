<?php

namespace App\Jobs;

use App\PurchaseInvoice;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ExportPurchaseInvoices implements ShouldQueue
{

    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $invoices;
    protected $user;
    protected $filesGenerated;
    protected $storageDirectory;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoices, User $user)
    {
        $this->invoices = PurchaseInvoice::findMany($invoices);
        $this->user = $user;
        $this->storageDirectory = 'temp';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->invoices->count() == 0) {
            return true;
        }

        $this->createIndividualXmlFiles();
        $this->createBulkedXmlFiles();

        // Inform user that the export has been completed
        Mail::to($this->user->email)->send(new \App\Mail\ExportPurchaseInvoices($this->invoices, $this->filesGenerated));
    }

    /**
     * Generate individual XML files for freight invoices.
     */
    public function createIndividualXmlFiles()
    {
        $freightInvoices = $this->invoices->where('type', '!=', 'D');

        foreach ($freightInvoices as $invoice) {

            $filename = $this->storageDirectory . '/' . $this->getFilename($invoice->invoice_number);

            // Generate the XML
            $xml = $invoice->getMultifreightXml();

            // Write to storage
            Storage::disk('local')->put($filename, $xml);

            // Add the filename to array of filenames
            $this->filesGenerated[] = storage_path() . '/app/' . $filename;

            $this->setInvoiceToProcessed($invoice);
        }
    }

    /**
     * Generate bulked XML files for duty invoices (for each carrier).
     */
    private function createBulkedXmlFiles()
    {
        $dutyInvoices = $this->invoices->where('type', '=', 'D');
        $groupedByCarrier = [];

        foreach ($dutyInvoices as $invoice) {
            $groupedByCarrier[$invoice->carrier->code][] = $invoice;
        }

        foreach ($groupedByCarrier as $carrier => $invoices) {

            $xml = null;

            foreach ($invoices as $invoice) {
                $xml .= $invoice->getMultifreightXml();
                $this->setInvoiceToProcessed($invoice);
            }

            $enc = '<?xml version="1.0" encoding="ISO-8859-1"?>';
            $xml = str_replace($enc, '', $xml);
            $xml = $enc . "\n<Multifreight>" . $xml . "\n</Multifreight>";

            $filename = $this->storageDirectory . '/' . $this->getFilename(false, $carrier);

            // Write to storage
            Storage::disk('local')->put($filename, $xml);

            // Add the filename to array of filenames
            $this->filesGenerated[] = storage_path() . '/app/' . $filename;
        }
    }

    /**
     * Get a filename for the XML file to be generated.
     *
     * @param string $invoiceNumber
     * @return string
     */
    private function getFilename($invoiceNumber = false, $carrier = false)
    {
        if ($carrier) {
            return $carrier . 'Bulk_' . date('dmy') . '_' . strtoupper(str_random(3)) . '.xml';
        }

        return $invoiceNumber . '_' . date('dmy') . '_' . strtoupper(str_random(3)) . '.xml';
    }

    /**
     * Mark the export as complete.
     *
     * @param type $invoice
     */
    function setInvoiceToProcessed($invoice)
    {
        $invoice->xml_generated = 1;
        $invoice->status = 2;
        $invoice->save();
    }

}
