<?php

namespace App\Console\Commands\PurchaseInvoices;

use App\PurchaseInvoice;
use App\PurchaseInvoiceCharge;
use App\PurchaseInvoiceLine;
use Illuminate\Console\Command;

class ImportDhlPurchaseInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-dhl-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files (pushed via SFTP by DHL) and populates purchase invoice tables';

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
     * An array of invoice numbers that have been created.
     *
     * @var array
     */
    protected $invoices;

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

        $this->sftpDirectory = '/home/dhlinv/invoices/';
        $this->archiveDirectory = 'archive';
        $this->invoices = [];

        /*
         * Order of fields in DHL file
         */
        $this->fields = ['Line Type', 'Billing Source', 'Original Invoice Number', 'Invoice Number', 'Station Code', 'Invoice Identifier', 'Invoice Type', 'Invoice Date', 'Payment Terms', 'Due Date', 'Parent Account', 'Billing Account', 'Billing Account Name', 'Billing Account Name (Additional)', 'Billing Address 1', 'Billing Address 2', 'Billing Address 3', 'Billing Postcode', 'Billing City', 'Billing State/Province', 'Billing Country Code', 'Billing Contact', 'VAT Number', 'Shipment Number', 'Shipment Date', 'Country Specific Label', 'Country Specific Value', 'Shipment Reference 1', 'Shipment Reference 2', 'Shipment Reference 3', 'Product', 'Product Name', 'Pieces', 'Origin', 'Orig Name', 'Orig Country Code', 'Orig Country Name', 'Senders Name', 'Senders Address 1', 'Senders Address 2', 'Senders Address 3', 'Senders Postcode', 'Senders City', 'Senders State/Province', 'Senders Country', 'Senders Contact', 'Destination', 'Dest Name', 'Dest Country Code', 'Dest Country Name', 'Receivers Name', 'Receivers Address 1', 'Receivers Address 2', 'Receivers Address 3', 'Receivers Postcode', 'Receivers City', 'Receivers State/Province', 'Receivers Country', 'Receivers Contact', 'Proof of Delivery/Name', 'Description of Contents', 'Event Description', 'Dimensions', 'Cust Scale Weight (A)', 'DHL Scale Weight (B)', 'Cust Vol Weight (V)', 'DHL Vol Weight (W)', 'Weight Flag', 'Weight (kg)', 'Currency', 'Total amount (excl. VAT)', 'Total amount (incl. VAT)', 'Tax Code', 'Total Tax', 'Tax Adjustment', 'Invoice Fee', 'Weight Charge', 'Weight Tax (VAT)', 'Other Charges 1', 'Other Charges 1 Amount', 'Other Charges 2', 'Other Charges 2 Amount', 'Discount 1', 'Discount 1 Amount', 'Discount 2', 'Discount 2 Amount', 'Discount 3', 'Discount 3 Amount', 'Total Extra Charges (XC)', 'Total Extra Charges Tax', 'XC1 Code', 'XC1 Name', 'XC1 Charge', 'XC1 Tax Code', 'XC1 Tax', 'XC1 Discount', 'XC1 Total', 'XC2 Code', 'XC2 Name', 'XC2 Charge', 'XC2 Tax Code', 'XC2 Tax', 'XC2 Discount', 'XC2 Total', 'XC3 Code', 'XC3 Name', 'XC3 Charge', 'XC3 Tax Code', 'XC3 Tax', 'XC3 Discount', 'XC3 Total', 'XC4 Code', 'XC4 Name', 'XC4 Charge', 'XC4 Tax Code', 'XC4 Tax', 'XC4 Discount', 'XC4 Total', 'XC5 Code', 'XC5 Name', 'XC5 Charge', 'XC5 Tax Code', 'XC5 Tax', 'XC5 Discount', 'XC5 Total', 'XC6 Code', 'XC6 Name', 'XC6 Charge', 'XC6 Tax Code', 'XC6 Tax', 'XC6 Discount', 'XC6 Total', 'XC7 Code', 'XC7 Name', 'XC7 Charge', 'XC7 Tax Code', 'XC7 Tax', 'XC7 Discount', 'XC7 Total', 'XC8 Code', 'XC8 Name', 'XC8 Charge', 'XC8 Tax Code', 'XC8 Tax', 'XC8 Discount', 'XC8 Total', 'XC9 Code', 'XC9 Name', 'XC9 Charge', 'XC9 Tax Code', 'XC9 Tax', 'XC9 Discount', 'XC9 Total'];
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

        if (count($this->invoices) == 0) {
            $this->error('No purchase invoices imported');
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

        $rowNumber = 1;
        $invoiceCreated = false;

        if (($handle = fopen($this->sftpDirectory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                if ($rowNumber >= 2) {
                    $row = $this->assignFieldNames($data);

                    if ($row['Line Type'] == 'I') {
                        $invoiceCreated = $this->createPurchaseInvoice($row);
                    }

                    if ($invoiceCreated && $row['Line Type'] == 'S') {
                        $purchaseInvoiceLine = PurchaseInvoiceLine::firstOrCreate([
                                    'carrier_consignment_number' => $row['Shipment Number'],
                                    'carrier_tracking_number' => $row['Shipment Number'],
                                    'purchase_invoice_id' => $this->purchaseInvoice->id,
                        ]);

                        $purchaseInvoiceLine->sender_name = $row['Senders Contact'];
                        $purchaseInvoiceLine->sender_company_name = $row['Senders Name'];
                        $purchaseInvoiceLine->sender_address1 = $row['Senders Address 1'];
                        $purchaseInvoiceLine->sender_address2 = $row['Senders Address 2'];
                        $purchaseInvoiceLine->sender_city = $row['Senders City'];
                        $purchaseInvoiceLine->sender_state = $row['Senders State/Province'];
                        $purchaseInvoiceLine->sender_postcode = $row['Senders Postcode'];
                        $purchaseInvoiceLine->sender_country_code = $row['Senders Country'];
                        $purchaseInvoiceLine->sender_account_number = $row['Billing Account'];
                        $purchaseInvoiceLine->recipient_name = $row['Receivers Contact'];
                        $purchaseInvoiceLine->recipient_company_name = $row['Receivers Name'];
                        $purchaseInvoiceLine->recipient_address1 = $row['Receivers Address 1'];
                        $purchaseInvoiceLine->recipient_address2 = $row['Receivers Address 2'];
                        $purchaseInvoiceLine->recipient_city = $row['Receivers City'];
                        $purchaseInvoiceLine->recipient_state = $row['Receivers State/Province'];
                        $purchaseInvoiceLine->recipient_postcode = $row['Receivers Postcode'];
                        $purchaseInvoiceLine->recipient_country_code = $row['Receivers Country'];
                        $purchaseInvoiceLine->ship_date = strtotime($row['Shipment Date']);
                        $purchaseInvoiceLine->shipment_reference = $row['Shipment Reference 1'];
                        $purchaseInvoiceLine->carrier_service = $row['Product'];
                        $purchaseInvoiceLine->carrier_packaging_code = null;
                        $purchaseInvoiceLine->carrier_pay_code = null;
                        $purchaseInvoiceLine->account_number1 = $row['Parent Account'];
                        $purchaseInvoiceLine->account_number2 = $row['Billing Account'];
                        $purchaseInvoiceLine->pieces = $row['Pieces'];
                        $purchaseInvoiceLine->weight = $row['Weight (kg)'];
                        $purchaseInvoiceLine->weight_uom = 'kg';
                        $purchaseInvoiceLine->billed_weight = $row['Weight (kg)'];
                        $purchaseInvoiceLine->pod_signature = strtotime($row['Proof of Delivery/Name']);
                        $purchaseInvoiceLine->update();

                        /*
                         * Create the charge lines.
                         */

                        if ($row['Weight Charge'] > 0) {
                            $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                        'code' => 'FRT',
                                        'description' => 'FREIGHT CHARGE',
                                        'amount' => $row['Weight Charge'],
                                        'currency_code' => $row['Currency'],
                                        'exchange_rate' => 1,
                                        'billed_amount' => round($row['Weight Charge'] + $row['Discount 1 Amount'], 2),
                                        'billed_amount_currency_code' => $row['Currency'],
                                        'vat_applied' => ($row['Weight Tax (VAT)'] > 0) ? 1 : 0,
                                        'vat' => $row['Weight Tax (VAT)'],
                                        'vat_rate' => ($row['Weight Tax (VAT)'] > 0) ? 20 : 0,
                                        'purchase_invoice_id' => $this->purchaseInvoice->id,
                                        'purchase_invoice_line_id' => $purchaseInvoiceLine->id,
                            ]);

                            $purchaseInvoiceCharge->setCarrierChargeId();
                        }

                        /*
                         * Other charges.
                         */

                        for ($i = 1; $i <= 2; $i++) {
                            if ($row["Other Charges $i Amount"] > 0) {
                                $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                            'code' => "OT$i",
                                            'description' => $row["Other Charges $i"],
                                            'amount' => $row["Other Charges $i Amount"],
                                            'currency_code' => $row['Currency'],
                                            'exchange_rate' => 1,
                                            'billed_amount' => $row["Other Charges $i Amount"],
                                            'billed_amount_currency_code' => $row['Currency'],
                                            'purchase_invoice_id' => $this->purchaseInvoice->id,
                                            'purchase_invoice_line_id' => $purchaseInvoiceLine->id,
                                ]);

                                $purchaseInvoiceCharge->setCarrierChargeId();
                            }
                        }

                        /*
                         * Additional charges.
                         */

                        for ($i = 1; $i <= 9; $i++) {
                            if (strlen($row["XC$i Code"]) == 2) {
                                $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                            'code' => $row["XC$i Code"],
                                            'description' => $row["XC$i Name"],
                                            'amount' => $row["XC$i Charge"],
                                            'currency_code' => $row['Currency'],
                                            'exchange_rate' => 1,
                                            'billed_amount' => round($row["XC$i Charge"] + $row["XC$i Discount"], 2),
                                            'billed_amount_currency_code' => $row['Currency'],
                                            'vat_applied' => ($row["XC$i Tax Code"] == 'A') ? 1 : 0,
                                            'vat' => $row["XC$i Tax"],
                                            'vat_rate' => ($row["XC$i Tax Code"] == 'A') ? 20 : 0,
                                            'purchase_invoice_id' => $this->purchaseInvoice->id,
                                            'purchase_invoice_line_id' => $purchaseInvoiceLine->id,
                                ]);

                                $purchaseInvoiceCharge->setCarrierChargeId();
                            }
                        }
                    }
                }

                $rowNumber++;
            }

            fclose($handle);
        }

        /*
         * Update additional information after the invoice has been imported.
         */
        foreach ($this->invoices as $invoiceNumber) {
            $purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(5)->first();
            $purchaseInvoice->setAdditionalValues();
            $purchaseInvoice->setTotalTaxable();
            $purchaseInvoice->setTotalNonTaxable();
        }
    }

    /**
     * Save and set the purchase invoice.
     *
     * @param type $line
     */
    private function createPurchaseInvoice($row)
    {
        $invoiceNumber = $row['Invoice Number'];

        $ignoredInvoiceTypes = ['CN INB', 'CN LOB', 'N'];

        if (in_array($row['Invoice Type'], $ignoredInvoiceTypes)) {
            $this->error("Credit note $invoiceNumber ignored");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(5)->first();

        if ($this->purchaseInvoice) {
            $this->error("Invoice $invoiceNumber skipped (already exists)");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
                    'invoice_number' => $invoiceNumber,
                    'account_number' => $row['Parent Account'],
                    'total' => $row['Total amount (incl. VAT)'],
                    'total_taxable' => 0,
                    'total_non_taxable' => 0,
                    'vat' => $row['Total Tax'],
                    'currency_code' => $row['Currency'],
                    'type' => $this->getInvoiceType($row['Invoice Type']),
                    'carrier_id' => 5,
                    'date' => strtotime($row['Invoice Date']),
        ]);

        $this->invoices[] = $invoiceNumber;

        return true;
    }

    /**
     * Get the invoice type.
     *
     * @param type $invoiceType
     * @return string
     */
    private function getInvoiceType($invoiceType)
    {
        switch ($invoiceType) {
            case 'DUTY':
                return 'D'; // duty & taxes
            default:
                return 'F'; // other
        }
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
