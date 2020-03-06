<?php

namespace App\Console\Commands\PurchaseInvoices;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceCharge;
use App\Models\PurchaseInvoiceLine;
use Illuminate\Console\Command;

class ImportPrimaryFreightPurchaseInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-primary-freight-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files (pushed via FTP by Primary Freight) and populates purchase invoice tables';

    /**
     * The SFTP directory that DHL upload invoice files.
     *
     * @var string
     */
    protected $sftpDirectory;

    /**
     * Folder that processed files are to be archived.
     *
     * @var string
     */
    protected $archiveDirectory;

    /**
     * Currently loaded purchase invoice.
     *
     * @var model
     */
    protected $purchaseInvoice;

    /**
     * Currently loaded purchase invoice line.
     *
     * @var model
     */
    protected $purchaseInvoiceLine;

    /**
     * @var type
     */
    protected $row;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->sftpDirectory = '/home/primaryfreight/invoices/';
        $this->archiveDirectory = 'archive';

        /*
         * Order of fields in PF file
         */
        $this->fields = ['Location', 'StartDate', 'EndDate', 'textbox45', 'Name', 'Date', 'ShipDate', 'TransactionID', 'ConsignmentNumber', 'TrackingNumber', 'QtyIn', 'QtyOut', 'Handling', 'Textbox80', 'Materials', 'Storage', 'textbox69', 'Special', 'FreightPP', 'Freight3', 'Total', 'Notes', 'textbox38', 'textbox39', 'textbox40', 'textbox41', 'Textbox83', 'textbox42', 'textbox43', 'textbox71', 'textbox65', 'textbox44', 'textbox59', 'textbox60', 'textbox13', 'textbox15', 'textbox17', 'Textbox84', 'textbox19', 'textbox21', 'textbox72', 'textbox66', 'textbox23', 'textbox25', 'textbox27'];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Checking '.$this->sftpDirectory.' for files to process');

        if ($handle = opendir($this->sftpDirectory)) {
            while (false !== ($file = readdir($handle))) {
                if (! is_dir($file) && $file != $this->archiveDirectory) {
                    $this->processFile($file);
                    $this->archiveFile($file);
                }
            }

            closedir($handle);
        }

        $this->info('Finished processing files');
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param type $data
     *
     * @return void
     */
    private function assignFieldNames($data)
    {
        $i = 0;
        foreach ($this->fields as $field) {
            $row[$field] = (isset($data[$i])) ? trim($data[$i]) : null;
            $i++;
        }

        return $row;
    }

    /**
     * Read the file contents and insert records.
     *
     * @param type $file
     */
    private function processFile($file)
    {
        $this->info("Processing file $file");

        $this->createPurchaseInvoice();

        $rowNumber = 1;
        $total = 0;

        if (($handle = fopen($this->sftpDirectory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                if ($rowNumber >= 2) {
                    $row = $this->assignFieldNames($data);

                    $rowTotal = preg_replace('/[^0-9,"."]/', '', $row['Total']);

                    if (is_numeric($rowTotal) && $rowTotal > 0) {
                        $total += $rowTotal;
                    }

                    if ($rowTotal > 0) {
                        // Lookup shipment
                        $shipment = \App\Models\Shipment::whereConsignmentNumber($row['ConsignmentNumber'])->first();

                        $purchaseInvoiceLine = new PurchaseInvoiceLine();
                        $purchaseInvoiceLine->purchase_invoice_id = $this->purchaseInvoice->id;
                        $purchaseInvoiceLine->carrier_consignment_number = strtoupper($row['ConsignmentNumber']);
                        $purchaseInvoiceLine->carrier_tracking_number = strtoupper($row['TrackingNumber']);
                        $purchaseInvoiceLine->sender_name = ($shipment) ? $shipment->sender_name : null;
                        $purchaseInvoiceLine->sender_company_name = ($shipment) ? $shipment->sender_company_name : null;
                        $purchaseInvoiceLine->sender_address1 = ($shipment) ? $shipment->sender_address1 : null;
                        $purchaseInvoiceLine->sender_address2 = ($shipment) ? $shipment->sender_address2 : null;
                        $purchaseInvoiceLine->sender_city = ($shipment) ? $shipment->sender_city : null;
                        $purchaseInvoiceLine->sender_state = ($shipment) ? $shipment->sender_state : null;
                        $purchaseInvoiceLine->sender_postcode = ($shipment) ? $shipment->sender_postcode : null;
                        $purchaseInvoiceLine->sender_country_code = ($shipment) ? $shipment->sender_country_code : null;
                        $purchaseInvoiceLine->sender_account_number = ($shipment) ? $shipment->sender_account_number : null;
                        $purchaseInvoiceLine->recipient_name = ($shipment) ? $shipment->recipient_name : null;
                        $purchaseInvoiceLine->recipient_company_name = ($shipment) ? $shipment->recipient_company_name : null;
                        $purchaseInvoiceLine->recipient_address1 = ($shipment) ? $shipment->recipient_address1 : null;
                        $purchaseInvoiceLine->recipient_address2 = ($shipment) ? $shipment->recipient_address2 : null;
                        $purchaseInvoiceLine->recipient_city = ($shipment) ? $shipment->recipient_city : null;
                        $purchaseInvoiceLine->recipient_state = ($shipment) ? $shipment->recipient_state : null;
                        $purchaseInvoiceLine->recipient_postcode = ($shipment) ? $shipment->recipient_postcode : null;
                        $purchaseInvoiceLine->recipient_country_code = ($shipment) ? $shipment->recipient_country_code : null;
                        $purchaseInvoiceLine->ship_date = ($shipment) ? $shipment->ship_date : time();
                        $purchaseInvoiceLine->shipment_reference = ($shipment) ? $shipment->shipment_reference : null;
                        $purchaseInvoiceLine->carrier_service = null;
                        $purchaseInvoiceLine->carrier_packaging_code = null;
                        $purchaseInvoiceLine->carrier_pay_code = null;
                        $purchaseInvoiceLine->account_number1 = null;
                        $purchaseInvoiceLine->account_number2 = null;
                        $purchaseInvoiceLine->pieces = $row['QtyOut'];
                        $purchaseInvoiceLine->weight = ($shipment) ? $shipment->weight : 0;
                        $purchaseInvoiceLine->weight_uom = 'kg';
                        $purchaseInvoiceLine->billed_weight = 0;
                        $purchaseInvoiceLine->pod_signature = ($shipment) ? $shipment->pod_signature : null;
                        $purchaseInvoiceLine->save();

                        /*
                         * Create a charge line.
                         */

                        $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                    'code' => 'FRT',
                                    'description' => 'FREIGHT CHARGE',
                                    'amount' => $rowTotal,
                                    'currency_code' => 'USD',
                                    'exchange_rate' => null,
                                    'billed_amount' => $rowTotal,
                                    'billed_amount_currency_code' => 'USD',
                                    'vat_applied' => 0,
                                    'vat' => 0,
                                    'vat_rate' => 0,
                                    'purchase_invoice_id' => $this->purchaseInvoice->id,
                                    'purchase_invoice_line_id' => $purchaseInvoiceLine->id,
                        ]);

                        $purchaseInvoiceCharge->setCarrierChargeId();
                    }
                }

                $rowNumber++;
            }

            fclose($handle);

            /**
             * Update additional information after the invoice has been imported.
             */
            $purchaseInvoice = PurchaseInvoice::find($this->purchaseInvoice->id);
            $purchaseInvoice->setAdditionalValues();
            $purchaseInvoice->setTotalTaxable();
            $purchaseInvoice->setTotalNonTaxable();

            $purchaseInvoice->total = $total;
            $purchaseInvoice->save();
        }
    }

    /**
     * Save and set the purchase invoice.
     *
     * @param type $line
     */
    private function createPurchaseInvoice()
    {
        $invoiceNumber = 'PF'.time();

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(12)->first();

        if ($this->purchaseInvoice) {
            $this->error("Invoice $invoiceNumber skipped (already exists)");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
                    'invoice_number' => $invoiceNumber,
                    'account_number' => 'N/A',
                    'total' => 0,
                    'total_taxable' => 0,
                    'total_non_taxable' => 0,
                    'vat' => 0,
                    'currency_code' => 'USD',
                    'type' => 'F',
                    'carrier_id' => 12,
                    'date' => time(),
        ]);

        return true;
    }

    /**
     * Move file to archive directory.
     *
     * @param string $file
     * @return bool
     */
    public function archiveFile($file)
    {
        $this->info("Archiving file $file");

        $originalFile = $this->sftpDirectory.$file;
        $archiveFile = $this->sftpDirectory.$this->archiveDirectory.'/'.$file;

        $this->info("Moving $originalFile to archive");

        if (! file_exists($originalFile)) {
            $this->error("Problem archiving $file  - file not found");
        }

        if (copy($originalFile, $archiveFile)) {
            unlink($originalFile);
            $this->info('File archived successfully');
        }
    }
}
