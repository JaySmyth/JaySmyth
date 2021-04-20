<?php

namespace App\Console\Commands\PurchaseInvoices;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceCharge;
use App\Models\PurchaseInvoiceLine;
use App\Models\Shipment;
use Illuminate\Console\Command;

class ImportDxPurchaseInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-dx-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files (pushed via FTP by DX) and populates purchase invoice tables';

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

        $this->sftpDirectory = '/home/dx/invoices/';
        $this->archiveDirectory = 'archive';
        $this->fields = [
            'Invoice Number', 'Invoice Date', 'Billing Account Number', 'Billing Account Name', 'Manifest Number', 'Collection Date', 'Collection Day of Week', 'Customer Account Number', 'Customer Account Name', 'Consignment Number', 'Service Description', 'Pack Size', 'Time Option',
            'Delivery Zone', 'Number of Items', 'Consignment Weight', 'Compensation Level', 'Consignment Price', 'Currency', 'VAT', 'Customer PO', 'Customer Reference', 'Consignee Name', 'DeliveryTown', 'Delivery Post Code', 'Tracking Numbers'
        ];
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
     * Read the file contents and insert records.
     *
     * @param  string  $file
     */
    private function processFile($file)
    {
        $this->info("Processing file $file");

        $rowNumber = 1;
        $totalTaxable = 0;
        $vat = 0;
        $totalFuelSurcharge = 0;

        if (($handle = fopen($this->sftpDirectory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                if ($rowNumber >= 2) {
                    $row = $this->assignFieldNames($data);

                    if ($rowNumber == 2) {
                        $this->createPurchaseInvoice($row);
                    }

                    if ($row['Service Description'] == 'VAT') {
                        $vat = floatval(str_replace(',', '', $row['Consignment Price']));
                        continue;
                    }

                    if ($row['Service Description'] == 'TRACKER FUEL SURCHARGE') {
                        $totalFuelSurcharge = floatval(str_replace(',', '', $row['Consignment Price']));
                        continue;
                    }

                    $totalTaxable += $row['Consignment Price'];
                }
                $rowNumber++;
            }

            // Calc FSC rate per shipment
            $fsc = $totalFuelSurcharge / $totalTaxable;

            $rowNumber = 1;
        }


        if (($handle = fopen($this->sftpDirectory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                if ($rowNumber >= 2) {
                    $row = $this->assignFieldNames($data);

                    // Ignore these rows
                    if (in_array($row['Service Description'], ['VAT', 'TRACKER FUEL SURCHARGE'])) {
                        continue;
                    }

                    // Lookup shipment
                    $shipment = $this->getShipment($row['Tracking Numbers'], $row['Customer Reference']);

                    $purchaseInvoiceLine = PurchaseInvoiceLine::firstOrcreate([
                        'carrier_consignment_number' => ($shipment) ? $shipment->carrier_consignment_number : $row['Consignment Number'],
                        'carrier_tracking_number' => ($shipment) ? $shipment->carrier_consignment_number : $row['Tracking Numbers'],
                        'purchase_invoice_id' => $this->purchaseInvoice->id,
                    ]);

                    $purchaseInvoiceLine->sender_name = ($shipment) ? $shipment->sender_name : null;
                    $purchaseInvoiceLine->sender_company_name = ($shipment) ? $shipment->sender_company_name : null;
                    $purchaseInvoiceLine->sender_address1 = ($shipment) ? $shipment->sender_address1 : null;
                    $purchaseInvoiceLine->sender_address2 = ($shipment) ? $shipment->sender_address2 : null;
                    $purchaseInvoiceLine->sender_city = ($shipment) ? $shipment->sender_city : null;
                    $purchaseInvoiceLine->sender_state = ($shipment) ? $shipment->sender_state : null;
                    $purchaseInvoiceLine->sender_postcode = ($shipment) ? $shipment->sender_postcode : null;
                    $purchaseInvoiceLine->sender_country_code = ($shipment) ? $shipment->sender_country_code : null;
                    $purchaseInvoiceLine->sender_account_number = ($shipment) ? $shipment->sender_account_number : null;
                    $purchaseInvoiceLine->recipient_name = ($shipment) ? $shipment->recipient_name : $row['Consignee Name'];
                    $purchaseInvoiceLine->recipient_company_name = ($shipment) ? $shipment->recipient_company_name : null;
                    $purchaseInvoiceLine->recipient_address1 = ($shipment) ? $shipment->recipient_address1 : null;
                    $purchaseInvoiceLine->recipient_address2 = ($shipment) ? $shipment->recipient_address2 : null;
                    $purchaseInvoiceLine->recipient_city = ($shipment) ? $shipment->recipient_city : null;
                    $purchaseInvoiceLine->recipient_state = ($shipment) ? $shipment->recipient_state : null;
                    $purchaseInvoiceLine->recipient_postcode = ($shipment) ? $shipment->recipient_postcode : $row['Delivery Post Code'];
                    $purchaseInvoiceLine->recipient_country_code = ($shipment) ? $shipment->recipient_country_code : null;
                    $purchaseInvoiceLine->ship_date = strtotime(str_replace('/', '.', $row['Collection Date']));
                    $purchaseInvoiceLine->shipment_reference = ($shipment) ? $shipment->shipment_reference : null;
                    $purchaseInvoiceLine->carrier_service = $row['Service Description'].'/'.$row['Delivery Zone'];
                    $purchaseInvoiceLine->carrier_packaging_code = $row['Pack Size'];
                    $purchaseInvoiceLine->carrier_pay_code = null;
                    $purchaseInvoiceLine->account_number1 = $row['Billing Account Number'];
                    $purchaseInvoiceLine->account_number2 = null;
                    $purchaseInvoiceLine->pieces = $row['Number of Items'];
                    $purchaseInvoiceLine->weight = ($shipment) ? $shipment->weight : null;
                    $purchaseInvoiceLine->weight_uom = 'kg';
                    $purchaseInvoiceLine->billed_weight = $row['Consignment Weight'];
                    $purchaseInvoiceLine->pod_signature = ($shipment) ? $shipment->pod_signature : null;
                    $purchaseInvoiceLine->save();

                    if ($row['Service Description'] == 'Out of Gauge') {
                        $this->applyCharge($row['Consignment Price'], 'ADH', 'OUT OF GUAGE', $purchaseInvoiceLine->id);
                    } else {
                        $this->applyCharge($row['Consignment Price'], 'FRT', 'FREIGHT CHARGE', $purchaseInvoiceLine->id);
                        $this->applyCharge($row['Consignment Price'] * $fsc, 'FSC', 'FUEL SURCHARGE', $purchaseInvoiceLine->id);
                    }
                }

                $rowNumber++;
            }

            fclose($handle);

            /**
             * Update additional information after the invoice has been imported.
             */

            $totalTaxable += $totalFuelSurcharge;

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
     * @param  array  $data
     *
     * @return void
     */
    private function assignFieldNames(array $data)
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
     * @param  type  $line
     */
    private function createPurchaseInvoice($row)
    {
        $invoiceNumber = (! empty($row['Invoice Number'])) ? $row['Invoice Number'] : false;

        if (! $invoiceNumber) {
            $this->error("Invoice number not found - check file format.");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(17)->first();

        if ($this->purchaseInvoice) {
            $this->error("Invoice $invoiceNumber skipped (already exists)");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
            'invoice_number' => $invoiceNumber,
            'account_number' => $row['Billing Account Number'],
            'total' => 0,
            'total_taxable' => 0,
            'total_non_taxable' => 0,
            'vat' => 0,
            'currency_code' => 'GBP',
            'type' => 'F',
            'carrier_id' => 17,
            'date' => strtotime(str_replace('/', '.', $row['Invoice Date'])),
        ]);

        $this->invoices[] = $invoiceNumber;

        return true;
    }

    protected function getShipment($trackingNumber, $reference)
    {
        return Shipment::whereIn('carrier_consignment_number', explode(';', $trackingNumber.';'.$reference))->where('carrier_id', 17)->first();
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
                'vat_applied' => 1,
                'vat' => 0,
                'vat_rate' => 20,
                'purchase_invoice_id' => $this->purchaseInvoice->id,
                'purchase_invoice_line_id' => $purchaseInvoiceLineId,
            ]);

            $purchaseInvoiceCharge->setCarrierChargeId();
        }
    }

    /**
     * Move file to archive directory.
     *
     * @param  string  $file
     *
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
