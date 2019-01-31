<?php

namespace App\Console\Commands\PurchaseInvoices;

use Illuminate\Console\Command;
use App\PurchaseInvoice;
use App\PurchaseInvoiceLine;
use App\PurchaseInvoiceCharge;

class ImportFedexPurchaseInvoices extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-fedex-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files (pushed via SFTP by FedEx) and populates purchase invoice tables';

    /**
     * The SFTP directory that FedEx upload invoice files.
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
     * An array of invoice numbers that have been created.
     *
     * @var array
     */
    protected $invoices;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->sftpDirectory = '/home/fedexinv/invoices/';
        $this->archiveDirectory = 'archive';
        $this->invoices = array();
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

        $lines = file($this->sftpDirectory . $file);

        foreach ($lines as $line):

            $recordType = substr($line, 0, 3);

            switch ($recordType):

                case "HA1":
                    $this->createPurchaseInvoice($line);
                    break;

                case "HB4":
                    $this->purchaseInvoice->vat = round(substr($line, 47, 21) / 100, 2);
                    $this->purchaseInvoice->total_taxable = round(substr($line, 71, 21) / 100, 2);
                    $this->purchaseInvoice->total_non_taxable = round(substr($line, 95, 21) / 100, 2);
                    $this->purchaseInvoice->save();
                    break;

                case "DB1":
                case "DB2":
                    $purchaseInvoiceLine = $this->getPurchaseInvoiceLine(substr($line, 28, 12));

                    if (substr($line, 44, 2) == 'SH') {
                        $purchaseInvoiceLine->sender_account_number = trim(substr($line, 46, 9));
                        $purchaseInvoiceLine->sender_name = trim(substr($line, 58, 35));
                        $purchaseInvoiceLine->sender_company_name = trim(substr($line, 93, 35));
                        $purchaseInvoiceLine->sender_address1 = trim(substr($line, 128, 35));
                        $purchaseInvoiceLine->sender_address2 = trim(substr($line, 163, 35));
                        $purchaseInvoiceLine->sender_city = trim(substr($line, 198, 30));
                        $purchaseInvoiceLine->sender_state = trim(substr($line, 228, 2));
                        $purchaseInvoiceLine->sender_postcode = trim(substr($line, 230, 10));
                        $purchaseInvoiceLine->sender_country_code = trim(substr($line, 240, 2));
                    } else {
                        $purchaseInvoiceLine->recipient_account_number = trim(substr($line, 46, 9));
                        $purchaseInvoiceLine->recipient_name = trim(substr($line, 58, 35));
                        $purchaseInvoiceLine->recipient_company_name = trim(substr($line, 93, 35));
                        $purchaseInvoiceLine->recipient_address1 = trim(substr($line, 128, 35));
                        $purchaseInvoiceLine->recipient_address2 = trim(substr($line, 163, 35));
                        $purchaseInvoiceLine->recipient_city = trim(substr($line, 198, 30));
                        $purchaseInvoiceLine->recipient_state = trim(substr($line, 228, 2));
                        $purchaseInvoiceLine->recipient_postcode = trim(substr($line, 230, 10));
                        $purchaseInvoiceLine->recipient_country_code = trim(substr($line, 240, 2));
                    }
                    $purchaseInvoiceLine->save();
                    break;

                case "DC1":
                    $purchaseInvoiceLine = $this->getPurchaseInvoiceLine(substr($line, 28, 12));
                    $purchaseInvoiceLine->ship_date = strtotime(substr($line, 44, 8));
                    $purchaseInvoiceLine->shipment_reference = trim(substr($line, 61, 40));
                    $purchaseInvoiceLine->carrier_service = substr($line, 56, 2);
                    $purchaseInvoiceLine->carrier_packaging_code = substr($line, 58, 2);
                    $purchaseInvoiceLine->carrier_pay_code = substr($line, 60, 1);
                    $purchaseInvoiceLine->account_number1 = substr($line, 206, 9);
                    $purchaseInvoiceLine->save();
                    break;

                case "DD1":
                    if (substr($line, 68, 8) != '00000000') {
                        $purchaseInvoiceLine = $this->getPurchaseInvoiceLine(substr($line, 28, 12));
                        $purchaseInvoiceLine->delivery_date = strtotime(substr($line, 68, 8) . ' ' . substr($line, 76, 4));
                        $purchaseInvoiceLine->pod_signature = trim(substr($line, 80, 22));
                        $purchaseInvoiceLine->save();
                    }
                    break;

                case "DE1":
                    $purchaseInvoiceLine = $this->getPurchaseInvoiceLine(substr($line, 28, 12));
                    $purchaseInvoiceLine->pieces = substr($line, 44, 5);
                    $purchaseInvoiceLine->weight_uom = substr($line, 49, 1);
                    $purchaseInvoiceLine->billed_weight = substr($line, 50, 7) / 10;
                    $purchaseInvoiceLine->weight = substr($line, 57, 7) / 10;
                    $purchaseInvoiceLine->length = substr($line, 64, 3);
                    $purchaseInvoiceLine->width = substr($line, 67, 3);
                    $purchaseInvoiceLine->height = substr($line, 70, 3);
                    $purchaseInvoiceLine->dims_uom = substr($line, 73, 1);
                    $purchaseInvoiceLine->volumetric_divisor = substr($line, 74, 3);
                    //$purchaseInvoiceLine->decl_val', (substr($line, 77, 15) / 1);
                    $purchaseInvoiceLine->value = substr($line, 92, 15) / 100;
                    $purchaseInvoiceLine->value_currency_code = substr($line, 122, 3);
                    $purchaseInvoiceLine->save();
                    break;

                case "DF1":
                    $purchaseInvoiceLine = $this->getPurchaseInvoiceLine(substr($line, 28, 12));
                    $values = $this->getDf1Values($line);
                    $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                'code' => substr($line, 44, 3),
                                'amount' => $values['amount'],
                                'currency_code' => substr($line, 63, 3),
                                'exchange_rate' => $values['exchangeRate'],
                                'billed_amount' => $values['billedAmount'],
                                'billed_amount_currency_code' => substr($line, 100, 3),
                                'purchase_invoice_id' => $this->purchaseInvoice->id,
                                'purchase_invoice_line_id' => $purchaseInvoiceLine->id
                    ]);

                    $purchaseInvoiceCharge->setCarrierChargeId();
                    break;

                case "DW1":
                    $purchaseInvoiceLine = $this->getPurchaseInvoiceLine(substr($line, 28, 12));
                    $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                'code' => substr($line, 44, 3),
                                'amount' => round(substr($line, 73, 15) / 100, 2),
                                'currency_code' => substr($line, 100, 3),
                                'exchange_rate' => 1,
                                'billed_amount' => round(substr($line, 73, 15) / 100, 2),
                                'billed_amount_currency_code' => substr($line, 100, 3),
                                'purchase_invoice_id' => $this->purchaseInvoice->id,
                                'purchase_invoice_line_id' => $purchaseInvoiceLine->id
                    ]);

                    $purchaseInvoiceCharge->setCarrierChargeId();
                    break;

            endswitch;

        endforeach;

        /**
         * Update additional information after the invoices have been imported.
         */
        foreach ($this->invoices as $invoiceNumber) {
            $purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(2)->first();
            $purchaseInvoice->setAdditionalValues();
        }
    }

    /**
     * Save and set the purchase invoice.
     *
     * @param type $line
     */
    private function createPurchaseInvoice($line)
    {
        $invoiceNumber = substr($line, 13, 9);

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(2)->first();

        if ($this->purchaseInvoice) {
            $this->error('Invoice ' . $invoiceNumber . ' skipped (already exists)');
            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
                    'invoice_number' => $invoiceNumber,
                    'account_number' => substr($line, 64, 9),
                    'total' => substr($line, 76, 15) / 100,
                    'currency_code' => substr($line, 92, 3),
                    'type' => $this->getInvoiceType(substr($line, 141, 1)),
                    'carrier_id' => 2,
                    'date' => strtotime(substr($line, 56, 8))
        ]);

        $this->invoices[] = $invoiceNumber;
    }

    /**
     * Get an existing or create a new purchase invoice line.
     *
     * @param type $consignmentNumber
     */
    private function getPurchaseInvoiceLine($consignmentNumber)
    {
        return PurchaseInvoiceLine::firstOrCreate([
                    'carrier_consignment_number' => $consignmentNumber,
                    'carrier_tracking_number' => $consignmentNumber,
                    'purchase_invoice_id' => $this->purchaseInvoice->id
        ]);
    }

    /**
     * Get the invoice type
     *
     * @param type $invoiceType
     * @return string
     */
    private function getInvoiceType($invoiceType)
    {
        switch ($invoiceType) {
            case 'I':
                return 'F'; // freight
            case 'C':
                return 'D'; // duty
            default:
                return 'O'; // other
        }
    }

    /**
     * Get charge amounts for DF1 record.
     *
     * @param type $line
     */
    private function getDf1Values($line)
    {
        $values = array();

        $values['amount'] = round(substr($line, 47, 15) / 100, 2);
        $values['amountSign'] = substr($line, 62, 1);

        if ($values['amountSign'] == '-') {
            $values['amount'] = round($values['amount'] * (-1), 2);
        }

        $values['billedAmount'] = round(substr($line, 84, 15) / 100, 2);
        $values['billedSign'] = substr($line, 99, 1);

        if ($values['billedSign'] == '-') {
            $values['billedAmount'] = round($values['billedAmount'] * (-1), 2);
        }

        $values['exchangeRate'] = round(substr($line, 66, 18) / 1000000000, 2);

        return $values;
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
