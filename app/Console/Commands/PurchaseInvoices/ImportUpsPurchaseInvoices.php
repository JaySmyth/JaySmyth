<?php

namespace App\Console\Commands\PurchaseInvoices;

use App\PurchaseInvoice;
use App\PurchaseInvoiceCharge;
use App\PurchaseInvoiceLine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class ImportUpsPurchaseInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:import-ups-purchase-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads purchase files (downloaded via FTP) and populates purchase invoice tables';

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

        $this->directory = '/home/upsinv/invoices/';
        $this->archiveDirectory = 'archive';
        $this->invoices = [];

        $this->host = 'ftp2.ups.com';
        $this->port = '10022';
        $this->username = 'global0513';
        $this->password = 'qs8zha4';

        $this->numberOfSummaryFields = 27;
        $this->numberOfDetailFields = 152;

        /*
         * Order of fields in UPS file (each file has a summary and details section)
         */
        $this->fields = [
            'summary' => ['Acct No', 'Inv No', 'Inv Date', 'Inv Type', 'Invoice Address Receiver', 'Invoice Address Street', 'Invoice Address City', 'Invoice Address Country', 'UPS VAT ID', 'Acct VAT ID', 'Currency Code', 'Total Inv Amt', 'Total Taxable Amt', 'Total NonTax Amt', 'VAT Amt', 'Tax Variance Amt', 'Tax Rate'],
            'details' => ['Acct No', 'Inv No', 'Inv Date', 'Lead Shipment Number', 'Pick up Date', 'Import Date', 'Tracking number', 'Pick up Record Number', 'WorldEase Number', '# of Parcels', 'Zone', 'Bill option code', 'Shipper Reference no. 1', 'Shipper Reference no. 2', 'Shipment Message 1', 'Shipment Message 2', 'Package Dimensions', 'UPS Service', 'Container Type', 'Charge Type', 'actual Weight', 'billed Weight', 'Unit of Measure', 'Insurance Value', 'FRT Gross charge', 'FRT Incentive', 'FRT Net charge', 'FRT Tax Indicator', 'VAT Exemption Article', 'Fuel Charge', 'Fuel incentive', 'Fuel Net charge', 'Fuel Tax Indicator', 'VAT Exemption Article', 'Inv rel charges Code', 'Inv related charge description', 'Inv related charge net', 'Inv related charge Tax indicator', 'VAT Exemption Article', 'ACC1 Code', 'ACC1 Description', 'ACC1 Gross Charge', 'ACC1 Incentive', 'ACC1 Net charge', 'ACC1 Tax indicator', 'VAT Exemption Article', 'ACC1 Message', 'ACC2 Code', 'ACC2 Description', 'ACC2 Gross Charge', 'ACC2 Incentive', 'ACC2 Net charge', 'ACC2 Tax indicator', 'VAT Exemption Article', 'ACC2 Message', 'ACC3 Code', 'ACC3 Description', 'ACC3 Gross Charge', 'ACC3 Incentive', 'ACC3 Net charge', 'ACC3 Tax indicator', 'VAT Exemption Article', 'ACC3 Message', 'ACC4 Code', 'ACC4 Description', 'ACC4 Gross Charge', 'ACC4 Incentive', 'ACC4 Net charge', 'ACC4 Tax indicator', 'VAT Exemption Article', 'ACC4 Message', 'ACC5 Code', 'ACC5 Description', 'ACC5 Gross Charge', 'ACC5 Incentive', 'ACC5 Net charge', 'ACC5 Tax indicator', 'VAT Exemption Article', 'ACC5 Message', 'ACC6 Code', 'ACC6 Description', 'ACC6 Gross Charge', 'ACC6 Incentive', 'ACC6 Net charge', 'ACC6 Tax indicator', 'VAT Exemption Article', 'ACC6 Message', 'ACC7 Code', 'ACC7 Description', 'ACC7 Gross Charge', 'ACC7 Incentive', 'ACC7 Net charge', 'ACC7 Tax indicator', 'VAT Exemption Article', 'ACC7 Message', 'ACC8 Code', 'ACC8 Description', 'ACC8 Gross Charge', 'ACC8 Incentive', 'ACC8 Net charge', 'ACC8 Tax indicator', 'VAT Exemption Article', 'ACC8 Message', 'Exchange rate', 'Import VAT Code', 'Import VAT Amount', 'Import VAT Tax indicator', 'Duty Code', 'Duty Amount', 'Duty Tax indicator', 'Shipm Value amount', 'Customs Number', 'Shipment Description', 'Harmonized Code', 'Shipper Name', 'Shipper Street', 'Shipper ZIP', 'Shipper City', 'Shipper Country', 'Consignee Name', 'Consignee Street', 'Consignee ZIP', 'Consignee City', 'Consignee Country', 'other Address Name', 'other Address Street', 'other Address ZIP', 'other Address City', 'other Address Country', 'COD Base Amount (currently not filled)'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Download the files from remote SFTP site
        $this->downloadFiles();

        $this->info('Checking '.$this->directory.' for files to process');

        if ($handle = opendir($this->directory)) {
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
     * Download files from UPS SFTP site.
     *
     * @return void
     */
    private function downloadFiles()
    {
        $this->line('Attempting to download files from remote SFTP site');

        // Connect to the remote host
        $filesystem = $this->connect();

        // Check connection was made
        if (! $filesystem) {
            $this->error('Unable to connect to SFTP host -> '.$this->host);
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Failed to download UPS purchase invoice'));

            return false;
        }

        $this->info('Connection established to SFTP host -> '.$this->host);

        $fileList = $filesystem->listContents();

        foreach ($fileList as $file):

            if ($this->validateFile($file['basename'])) {

                // Open a local file to write to
                $handle = fopen($this->directory.'/'.$file['basename'], 'w');

                $contents = $filesystem->read($file['path']);

                fwrite($handle, $contents);

                fclose($handle);
            }

        endforeach;
    }

    /**
     * Upload file to host.
     *
     * @param type $filePath
     * @param type $host
     * @return type
     */
    protected function connect()
    {
        $this->line('Connecting to remote host: '.$this->host.':'.$this->port);

        $adapter = new SftpAdapter([
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'root' => '/in',
            'timeout' => 10,
            'directoryPerm' => 755,
        ]);

        try {
            $filesystem = new Filesystem($adapter);
        } catch (\Exception $exc) {
            $this->error($exc->getMessage());

            return false;
        }

        return $filesystem;
    }

    /**
     * Check if a file has been downloaded before and returns filename.
     *
     * @param type $string
     * @return string filename
     */
    private function validateFile($filename)
    {
        // Check the current directory and the archive directory to see if the file has already been downloaded
        if (file_exists($this->directory.'/'.$filename) || file_exists($this->directory.$this->archiveDirectory.'/'.$filename)) {
            $this->error("File $filename has already been downloaded");

            return false;
        }

        return true;
    }

    /**
     * @param type $file
     * @return bool
     */
    private function processFile($file)
    {
        $invoice = $this->readFile($this->directory.$file);

        foreach ($invoice['summary'] as $summary) {
            if ($this->createPurchaseInvoice($summary)) {

                // Loop through the invoice details section of the invoice array
                foreach ($invoice['details'] as $details) {

                    // Only read details that apply to this invoice number / acct number
                    if ($details['Inv No'] == $summary['Inv No'] && $details['Acct No'] == $summary['Acct No']) {
                        $purchaseInvoiceLine = PurchaseInvoiceLine::firstOrcreate([
                            'carrier_consignment_number' => ($details['Tracking number']) ? $details['Tracking number'] : strtoupper(Str::random(18)),
                            'carrier_tracking_number' => ($details['Tracking number']) ? $details['Tracking number'] : strtoupper(Str::random(18)),
                            'purchase_invoice_id' => $this->purchaseInvoice->id,
                        ]);

                        if (strlen($details['Tracking number']) <= 0 && strlen($details['Inv related charge description']) > 0 && ! $purchaseInvoiceLine->carrier_service) {
                            $carrierService = $details['Inv related charge description'];
                        } else {
                            $carrierService = $purchaseInvoiceLine->carrier_service ?: $details['UPS Service'];
                        }

                        $purchaseInvoiceLine->sender_company_name = $purchaseInvoiceLine->sender_company_name ?: $details['Shipper Name'];
                        $purchaseInvoiceLine->sender_address1 = $purchaseInvoiceLine->sender_address1 ?: $details['Shipper Street'];
                        $purchaseInvoiceLine->sender_city = $purchaseInvoiceLine->sender_city ?: $details['Shipper City'];
                        $purchaseInvoiceLine->sender_postcode = $purchaseInvoiceLine->sender_postcode ?: $details['Shipper ZIP'];
                        $purchaseInvoiceLine->sender_account_number = $purchaseInvoiceLine->sender_account_number ?: $details['Acct No']; //////////////////////////// - check this value
                        $purchaseInvoiceLine->sender_country_code = $purchaseInvoiceLine->sender_country_code ?: $details['Shipper Country'];
                        $purchaseInvoiceLine->recipient_name = $purchaseInvoiceLine->recipient_name ?: $details['Consignee Name'];
                        $purchaseInvoiceLine->recipient_address1 = $purchaseInvoiceLine->recipient_address1 ?: $details['Consignee Street'];
                        $purchaseInvoiceLine->recipient_city = $purchaseInvoiceLine->recipient_city ?: $details['Consignee City'];
                        $purchaseInvoiceLine->recipient_postcode = $purchaseInvoiceLine->recipient_postcode ?: $details['Consignee ZIP'];
                        $purchaseInvoiceLine->recipient_country_code = $purchaseInvoiceLine->recipient_country_code ?: $details['Consignee Country'];
                        $purchaseInvoiceLine->ship_date = $purchaseInvoiceLine->ship_date ?: strtotime($details['Pick up Date']);
                        $purchaseInvoiceLine->shipment_reference = $purchaseInvoiceLine->shipment_reference ?: $details['Shipper Reference no. 1'];
                        $purchaseInvoiceLine->carrier_service = $carrierService;
                        $purchaseInvoiceLine->carrier_packaging_code = $purchaseInvoiceLine->carrier_packaging_code ?: $details['Container Type'];
                        $purchaseInvoiceLine->carrier_pay_code = $purchaseInvoiceLine->carrier_pay_code ?: $details['Bill option code'];
                        $purchaseInvoiceLine->account_number1 = $purchaseInvoiceLine->account_number1 ?: $details['Acct No'];
                        $purchaseInvoiceLine->pieces = $purchaseInvoiceLine->pieces ?: $details['# of Parcels'];
                        $purchaseInvoiceLine->weight = $purchaseInvoiceLine->weight ?: $details['actual Weight'];
                        $purchaseInvoiceLine->weight_uom = $purchaseInvoiceLine->weight_uom ?: $details['Unit of Measure'];
                        $purchaseInvoiceLine->billed_weight = $purchaseInvoiceLine->billed_weight ?: $details['billed Weight'];
                        $purchaseInvoiceLine->value = $purchaseInvoiceLine->value ?: $details['Shipm Value amount']; //////////////////////////// - check this value
                        $purchaseInvoiceLine->purchase_invoice_id = $this->purchaseInvoice->id;
                        $purchaseInvoiceLine->save();

                        // Each detail line of the UPS invoice may have multiple charges (detailed in array below)
                        $charges = [
                            $details['Charge Type'] => ['amount' => 'FRT Net charge', 'tax' => 'FRT Tax Indicator'], // The main charge type of the detail line (SHP/RTN/ADJ)
                            'FSC' => ['amount' => 'Fuel Net charge', 'tax' => 'Fuel Tax Indicator'],
                            $details['Inv rel charges Code'] => ['amount' => 'Inv related charge net', 'tax' => 'Inv related charge Tax indicator'],
                            $details['ACC1 Code'] => ['amount' => 'ACC1 Net charge', 'tax' => 'ACC1 Tax indicator'],
                            $details['ACC2 Code'] => ['amount' => 'ACC2 Net charge', 'tax' => 'ACC2 Tax indicator'],
                            $details['ACC3 Code'] => ['amount' => 'ACC3 Net charge', 'tax' => 'ACC3 Tax indicator'],
                            $details['ACC4 Code'] => ['amount' => 'ACC4 Net charge', 'tax' => 'ACC4 Tax indicator'],
                            $details['ACC5 Code'] => ['amount' => 'ACC5 Net charge', 'tax' => 'ACC5 Tax indicator'],
                            $details['ACC6 Code'] => ['amount' => 'ACC6 Net charge', 'tax' => 'ACC6 Tax indicator'],
                            $details['ACC7 Code'] => ['amount' => 'ACC7 Net charge', 'tax' => 'ACC7 Tax indicator'],
                            $details['ACC8 Code'] => ['amount' => 'ACC8 Net charge', 'tax' => 'ACC8 Tax indicator'],
                        ];

                        // Insert a charge line for each charge with a value greater than zero
                        foreach ($charges as $chargeType => $charge):

                            if ($details[$charge['amount']] > 0) {
                                $vatAppliedToCharge = false;

                                if ($details[$charge['tax']] == 1) {
                                    $vatAppliedToCharge = true;
                                }

                                $purchaseInvoiceCharge = PurchaseInvoiceCharge::firstOrCreate([
                                    'code' => $chargeType,
                                    'amount' => $details[$charge['amount']],
                                    'currency_code' => $summary['Currency Code'],
                                    'exchange_rate' => 1,
                                    'billed_amount' => $details[$charge['amount']],
                                    'billed_amount_currency_code' => $summary['Currency Code'],
                                    'vat_applied' => $vatAppliedToCharge,
                                    'vat' => ($vatAppliedToCharge) ? ($details[$charge['amount']] / 100) * 20 : 0,
                                    'vat_rate' => ($vatAppliedToCharge) ? 20 : 0,
                                    'purchase_invoice_id' => $this->purchaseInvoice->id,
                                    'purchase_invoice_line_id' => $purchaseInvoiceLine->id,
                                ]);

                                $purchaseInvoiceCharge->setCarrierChargeId();
                            }
                        endforeach;
                    }
                }

                $this->info('Setting additional values');

                /*
                 * Update additional information after the invoice has been imported.
                 */
                $this->purchaseInvoice->setAdditionalValues();

                $this->info('Finished setting additional values');
            }

            return true;
        }
    }

    /**
     * Read the downloaded file into an array (with mapped field names).
     *
     * @param type $path
     * @return type
     */
    private function readFile($path)
    {
        $fileArray = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $data = [];
        $invoice = [];
        $i = 0;

        foreach ($fileArray as $line) {

            // Split the line into array (fields separated with ";")
            $data[$i] = explode(';', $line, -1);

            if ($data[$i][0] != 'Acct No') { // Ignore heading row
                $numberOfFields = count($data[$i]);
                switch ($numberOfFields) {
                    case $this->numberOfSummaryFields:
                        $type = 'summary';
                        break;
                    case $this->numberOfDetailFields:
                        $type = 'details';
                        break;
                    default:
                        $type = 'unknown';
                        $this->error("Number of fields not recognised on line $i");
                        break;
                }

                if ($type != 'unknown') {
                    // Build the invoice array with mapped field names
                    for ($field = 0; $field < $numberOfFields; $field++) {
                        if (isset($this->fields[$type][$field])) {
                            $key = $this->fields[$type][$field];
                            $invoice[$type][$i][$key] = $data[$i][$field];
                        }
                    }
                }
            }
            $i++;
        }

        $this->info("Finished reading file. File contained $i lines");

        return $invoice;
    }

    /**
     * Save and set the purchase invoice.
     *
     * @param type $line
     */
    private function createPurchaseInvoice($summary)
    {
        $invoiceNumber = $summary['Inv No'];

        $this->purchaseInvoice = PurchaseInvoice::whereInvoiceNumber($invoiceNumber)->whereCarrierId(3)->first();

        if ($this->purchaseInvoice) {
            $this->error("Invoice $invoiceNumber skipped (already exists)");

            return false;
        }

        $this->purchaseInvoice = PurchaseInvoice::create([
            'invoice_number' => $invoiceNumber,
            'account_number' => $summary['Acct No'],
            'total' => $summary['Total Inv Amt'],
            'total_taxable' => $summary['Total Taxable Amt'],
            'total_non_taxable' => $summary['Total NonTax Amt'],
            'vat' => $summary['VAT Amt'],
            'currency_code' => $summary['Currency Code'],
            'type' => $this->getInvoiceType($summary['Inv Type']),
            'carrier_id' => 3,
            'date' => strtotime($summary['Inv Date']),
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
            case '01':
                return 'F'; // freight
            case '06':
            case '12':
                return 'D'; // duty & taxes
            default:
                return 'O'; // other
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

        $originalFile = $this->directory.$file;
        $archiveFile = $this->directory.$this->archiveDirectory.'/'.$file;

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
