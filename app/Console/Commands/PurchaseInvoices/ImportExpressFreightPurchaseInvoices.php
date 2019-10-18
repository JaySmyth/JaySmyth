<?php

namespace App\Console\Commands\PurchaseInvoices;

use App\PurchaseInvoice;
use App\PurchaseInvoiceLine;
use App\PurchaseInvoiceCharge;
use Illuminate\Console\Command;

class ImportExpressFreightPurchaseInvoices extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-express-freight-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files (pushed via FTP by Express Freight) and populates purchase invoice tables';

    /**
     * The SFTP directory that Express Freight upload invoice files.
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
     *
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

        $this->sftpDirectory = '/home/expressfreight/invoices/';
        $this->archiveDirectory = 'archive';
        $this->fields = array('Consignment Number', 'Return', 'Dispatch Date', 'City', 'Type', 'No.', 'Description', 'Quantity', 'Consignee', 'Unit of Measure Code', 'Fuel Surcharge Amount', 'Line Amount Excl. VAT', 'Deferral Code', 'Consignment2', 'Post Code');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Checking ' . $this->sftpDirectory . ' for files to process');

        if ($handle = opendir($this->sftpDirectory)) {
            while (false !== ($file = readdir($handle))) {
                if (!is_dir($file) && $file != $this->archiveDirectory) {
                    $this->processFile($file);
                    $this->archiveFile($file);
                }
            }

            closedir($handle);
        }

        $this->info('Finished processing files');
    }

    /**
     * Read the file contents and insert records.
     *
     * @param type $file
     */
    private function processFile($file)
    {
        $this->info("Processing file $file");

        $this->createPurchaseInvoice($file);

        $rowNumber = 1;
        $totalTaxable = 0;

        if (($handle = fopen($this->sftpDirectory . $file, 'r')) !== false) {

            while (($data = fgetcsv($handle, 2000, ',')) !== false) {

                if ($rowNumber >= 2) {

                    $row = $this->assignFieldNames($data);

                    // Lookup shipment
                    $shipment = \App\Shipment::whereConsignmentNumber($row['Consignment Number'])->whereIn('carrier_id', [14])->first();

                    $purchaseInvoiceLine = new PurchaseInvoiceLine();
                    $purchaseInvoiceLine->purchase_invoice_id = $this->purchaseInvoice->id;
                    $purchaseInvoiceLine->carrier_consignment_number = ($shipment) ? $shipment->carrier_consignment_number : null;
                    $purchaseInvoiceLine->carrier_tracking_number = ($shipment) ? $shipment->carrier_tracking_number : null;
                    $purchaseInvoiceLine->sender_name = ($shipment) ? $shipment->sender_name : null;
                    $purchaseInvoiceLine->sender_company_name = ($shipment) ? $shipment->sender_company_name : null;
                    $purchaseInvoiceLine->sender_address1 = ($shipment) ? $shipment->sender_address1 : null;
                    $purchaseInvoiceLine->sender_address2 = ($shipment) ? $shipment->sender_address2 : null;
                    $purchaseInvoiceLine->sender_city = ($shipment) ? $shipment->sender_city : null;
                    $purchaseInvoiceLine->sender_state = ($shipment) ? $shipment->sender_state : null;
                    $purchaseInvoiceLine->sender_postcode = ($shipment) ? $shipment->sender_postcode : null;
                    $purchaseInvoiceLine->sender_country_code = ($shipment) ? $shipment->sender_country_code : null;
                    $purchaseInvoiceLine->sender_account_number = ($shipment) ? $shipment->sender_account_number : null;
                    $purchaseInvoiceLine->recipient_name = ($shipment) ? $shipment->recipient_name : $row['Consignee'];
                    $purchaseInvoiceLine->recipient_company_name = ($shipment) ? $shipment->recipient_company_name : null;
                    $purchaseInvoiceLine->recipient_address1 = ($shipment) ? $shipment->recipient_address1 : null;
                    $purchaseInvoiceLine->recipient_address2 = ($shipment) ? $shipment->recipient_address2 : null;
                    $purchaseInvoiceLine->recipient_city = ($shipment) ? $shipment->recipient_city : null;
                    $purchaseInvoiceLine->recipient_state = ($shipment) ? $shipment->recipient_state : null;
                    $purchaseInvoiceLine->recipient_postcode = ($shipment) ? $shipment->recipient_postcode : $row['Post Code'];
                    $purchaseInvoiceLine->recipient_country_code = ($shipment) ? $shipment->recipient_country_code : null;
                    $purchaseInvoiceLine->ship_date = strtotime(str_replace('/', '.', $row['Dispatch Date']));
                    $purchaseInvoiceLine->shipment_reference = ($shipment) ? $shipment->shipment_reference : null;
                    $purchaseInvoiceLine->carrier_service = $row['Description'];
                    $purchaseInvoiceLine->carrier_packaging_code = $row['Unit of Measure Code'];
                    $purchaseInvoiceLine->carrier_pay_code = null;
                    $purchaseInvoiceLine->account_number1 = null;
                    $purchaseInvoiceLine->account_number2 = null;
                    $purchaseInvoiceLine->pieces = $row['Quantity'];
                    $purchaseInvoiceLine->weight = ($shipment) ? $shipment->weight : null;
                    $purchaseInvoiceLine->weight_uom = 'kg';
                    $purchaseInvoiceLine->billed_weight = ($shipment) ? $shipment->weight : null;
                    $purchaseInvoiceLine->pod_signature = ($shipment) ? $shipment->pod_signature : null;
                    $purchaseInvoiceLine->save();

                    $this->applyCharge($row['Line Amount Excl. VAT'] - $row['Fuel Surcharge Amount'], 'FRT', 'FREIGHT CHARGE', $purchaseInvoiceLine->id);
                    $this->applyCharge($row['Fuel Surcharge Amount'], 'FSC', 'FUEL SURCHARGE', $purchaseInvoiceLine->id);

                    $totalTaxable += $purchaseInvoiceLine->charges->sum('billed_amount');

                }

                $rowNumber++;
            }

            fclose($handle);

            /**
             * Update additional information after the invoice has been imported.
             */

            $vat = ($totalTaxable / 100) * 20;
            $purchaseInvoice = PurchaseInvoice::find($this->purchaseInvoice->id);
            $purchaseInvoice->setAdditionalValues();
            $purchaseInvoice->total = $totalTaxable + $vat;
            $purchaseInvoice->total_taxable = $totalTaxable;
            $purchaseInvoice->total_non_taxable = 0;
            $purchaseInvoice->vat = $vat;
            $purchaseInvoice->save();
        }
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
     * Save and set the purchase invoice.
     *
     * @param type $line
     */
    private function createPurchaseInvoice($file)
    {
        $invoiceNumber = 'EXP' . str_replace(['.csv', ''], '', $file);

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(14)->first();

        if ($this->purchaseInvoice) {
            $this->error("Invoice $invoiceNumber skipped (already exists)");
            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
            'invoice_number' => $invoiceNumber,
            'account_number' => 'I012',
            'total' => 0,
            'total_taxable' => 0,
            'total_non_taxable' => 0,
            'vat' => 0,
            'currency_code' => 'GBP',
            'type' => 'F',
            'carrier_id' => 14,
            'date' => time()
        ]);

        $this->invoices[] = $invoiceNumber;

        return true;
    }

    /**
     * Apply a known charge.
     *
     * @param $value
     * @param $code
     * @param $description
     * @param $purchaseInvoiceLineId
     */
    private function applyCharge($value, $code, $description, $purchaseInvoiceLineId)
    {
        if ($value > 0) {
            $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                'code' => $code,
                'description' => $description,
                'amount' => $value,
                'currency_code' => 'GBP',
                'exchange_rate' => 1,
                'billed_amount' => $value,
                'billed_amount_currency_code' => 'GBP',
                'vat_applied' => 0,
                'vat' => 0,
                'vat_rate' => 0,
                'purchase_invoice_id' => $this->purchaseInvoice->id,
                'purchase_invoice_line_id' => $purchaseInvoiceLineId
            ]);

            $purchaseInvoiceCharge->setCarrierChargeId();
        }
    }

    /**
     * Move file to archive directory.
     *
     * @param string $file
     * @return boolean
     */
    function archiveFile($file)
    {
        $this->info("Archiving file $file");

        $originalFile = $this->sftpDirectory . $file;
        $archiveFile = $this->sftpDirectory . $this->archiveDirectory . '/' . $file;

        $this->info("Moving $originalFile to archive");

        if (!file_exists($originalFile)) {
            $this->error("Problem archiving $file  - file not found");
        }

        if (copy($originalFile, $archiveFile)) {
            unlink($originalFile);
            $this->info("File archived successfully");
        }
    }

}
