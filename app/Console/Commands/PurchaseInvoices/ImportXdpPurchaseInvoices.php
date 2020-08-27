<?php

namespace App\Console\Commands\PurchaseInvoices;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceCharge;
use App\Models\PurchaseInvoiceLine;
use Illuminate\Console\Command;

class ImportXdpPurchaseInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-xdp-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files and populates purchase invoice tables';

    /**
     * The SFTP directory that XDP upload invoice files.
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

        $this->sftpDirectory    = '/home/xdp/invoices/';
        $this->archiveDirectory = 'archive';
        $this->fields           = ['Dispatch Date', 'Consignment Number', 'Shipment Reference', 'Postcode', 'Zone', 'Pieces', 'Weight', 'Service', 'Line Amount Ex. Vat', 'Fuel Surcharge'];
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
                if ( ! is_dir($file) && $file != $this->archiveDirectory) {
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
     * @param  type  $file
     */
    private function processFile($file)
    {
        $this->info("Processing file $file");

        $rowNumber = 1;

        if (($handle = fopen($this->sftpDirectory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                if ($rowNumber == 1) {
                    $this->createPurchaseInvoice($handle);
                }

                $row = $this->assignFieldNames($data);

                if ($rowNumber >= 20 && !empty($row['Consignment Number'])) {

                    // Lookup shipment
                    $shipment = \App\Models\Shipment::whereCarrierConsignmentNumber($row['Consignment Number'])->where('carrier_id', 16)->first();

                    $purchaseInvoiceLine                             = new PurchaseInvoiceLine();
                    $purchaseInvoiceLine->purchase_invoice_id        = $this->purchaseInvoice->id;
                    $purchaseInvoiceLine->carrier_consignment_number = ($shipment) ? $shipment->carrier_consignment_number : null;
                    $purchaseInvoiceLine->carrier_tracking_number    = ($shipment) ? $shipment->carrier_tracking_number : null;
                    $purchaseInvoiceLine->sender_name                = ($shipment) ? $shipment->sender_name : null;
                    $purchaseInvoiceLine->sender_company_name        = ($shipment) ? $shipment->sender_company_name : null;
                    $purchaseInvoiceLine->sender_address1            = ($shipment) ? $shipment->sender_address1 : null;
                    $purchaseInvoiceLine->sender_address2            = ($shipment) ? $shipment->sender_address2 : null;
                    $purchaseInvoiceLine->sender_city                = ($shipment) ? $shipment->sender_city : null;
                    $purchaseInvoiceLine->sender_state               = ($shipment) ? $shipment->sender_state : null;
                    $purchaseInvoiceLine->sender_postcode            = ($shipment) ? $shipment->sender_postcode : null;
                    $purchaseInvoiceLine->sender_country_code        = ($shipment) ? $shipment->sender_country_code : null;
                    $purchaseInvoiceLine->sender_account_number      = ($shipment) ? $shipment->sender_account_number : null;
                    $purchaseInvoiceLine->recipient_name             = ($shipment) ? $shipment->recipient_name : null;
                    $purchaseInvoiceLine->recipient_company_name     = ($shipment) ? $shipment->recipient_company_name : null;
                    $purchaseInvoiceLine->recipient_address1         = ($shipment) ? $shipment->recipient_address1 : null;
                    $purchaseInvoiceLine->recipient_address2         = ($shipment) ? $shipment->recipient_address2 : null;
                    $purchaseInvoiceLine->recipient_city             = ($shipment) ? $shipment->recipient_city : null;
                    $purchaseInvoiceLine->recipient_state            = ($shipment) ? $shipment->recipient_state : null;
                    $purchaseInvoiceLine->recipient_postcode         = ($shipment) ? $shipment->recipient_postcode : $row['Postcode'];
                    $purchaseInvoiceLine->recipient_country_code     = ($shipment) ? $shipment->recipient_country_code : null;
                    $purchaseInvoiceLine->ship_date                  = strtotime(str_replace('/', '.', $row['Dispatch Date']));
                    $purchaseInvoiceLine->shipment_reference         = ($shipment) ? $shipment->shipment_reference : $row['Shipment Reference'];
                    $purchaseInvoiceLine->carrier_service            = $row['Service'];
                    $purchaseInvoiceLine->carrier_packaging_code     = null;
                    $purchaseInvoiceLine->carrier_pay_code           = null;
                    $purchaseInvoiceLine->account_number1            = null;
                    $purchaseInvoiceLine->account_number2            = null;
                    $purchaseInvoiceLine->pieces                     = $row['Pieces'];
                    $purchaseInvoiceLine->weight                     = $row['Weight'];
                    $purchaseInvoiceLine->weight_uom                 = 'kg';
                    $purchaseInvoiceLine->billed_weight              = $row['Weight'];
                    $purchaseInvoiceLine->pod_signature              = ($shipment) ? $shipment->pod_signature : null;
                    $purchaseInvoiceLine->save();

                    if(stristr($row['Consignment Number'], 'SMS')){
                        $this->applyCharge($row['Line Amount Ex. Vat'], 'SMS', 'SMS PRE-ALERTS', $purchaseInvoiceLine->id);
                    } else {
                        $this->applyCharge($row['Line Amount Ex. Vat'], 'FRT', 'FREIGHT CHARGE', $purchaseInvoiceLine->id);
                        $this->applyCharge($row['Fuel Surcharge'], 'FSC', 'FUEL SURCHARGE', $purchaseInvoiceLine->id);
                    }

                }

                $rowNumber++;
            }

            fclose($handle);
        }
    }


    /**
     * Save and set the purchase invoice.
     *
     * @param  type  $line
     */
    private function createPurchaseInvoice($handle)
    {
        $fileRow = 1;

        while (($data = fgetcsv($handle, 2000, ',')) !== false && $fileRow <= 15) {

            // Invoice number
            if ($fileRow == 7) {
                $invoiceNumber = $data[1];
            }

            //Invoice date
            if ($fileRow == 8) {
                $invoiceDate = $data[1];
            }

            //Account code
            if ($fileRow == 9) {
                $accountCode = $data[1];
            }

            //P/O or Ref No
            if ($fileRow == 10) {
                $poRef = $data[1];
            }

            //NET Total
            if ($fileRow == 11) {
                $netTotal = $data[1];
            }

            //Surcharge
            if ($fileRow == 13) {
                $surcharge = $data[1];
            }

            //VAT Total
            if ($fileRow == 14) {
                $vatTotal = $data[1];
            }

            //INVOICE Total
            if ($fileRow == 15) {
                $invoiceTotal = $data[1];
                break;
            }

            $fileRow++;
        }

        if ( ! $invoiceNumber) {
            $this->error("Invoice number not found - check file format.");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(16)->first();

        if ($this->purchaseInvoice) {
            $this->error("Invoice $invoiceNumber skipped (already exists)");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
            'invoice_number'    => $invoiceNumber,
            'account_number'    => $accountCode,
            'total'             => $invoiceTotal,
            'total_taxable'     => $netTotal + $surcharge,
            'total_non_taxable' => 0,
            'vat'               => $vatTotal,
            'currency_code'     => 'GBP',
            'type'              => 'F',
            'carrier_id'        => 16,
            'date'              => strtotime(str_replace('/', '.', $invoiceDate)),
        ]);

        $this->invoices[] = $invoiceNumber;

        return true;
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param  type  $data
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
                'code'                        => $code,
                'description'                 => $description,
                'amount'                      => $value,
                'currency_code'               => 'GBP',
                'exchange_rate'               => 1,
                'billed_amount'               => $value,
                'billed_amount_currency_code' => 'GBP',
                'vat_applied'                 => 1,
                'vat'                         => 0,
                'vat_rate'                    => 20,
                'purchase_invoice_id'         => $this->purchaseInvoice->id,
                'purchase_invoice_line_id'    => $purchaseInvoiceLineId,
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
        $archiveFile  = $this->sftpDirectory.$this->archiveDirectory.'/'.$file;

        $this->info("Moving $originalFile to archive");

        if ( ! file_exists($originalFile)) {
            $this->error("Problem archiving $file  - file not found");
        }

        if (copy($originalFile, $archiveFile)) {
            unlink($originalFile);
            $this->info('File archived successfully');
        }
    }
}
