<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Validator;

class UpdatePrimaryFreightShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:update-primary-freight-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Completes Primary Freight shipments with carrier consignment, pieces, weight and creates an easypost tracker';

    /**
     * Full path to be processed.
     *
     * @var string
     */
    protected $directory = '/home/primaryfreight/dispatched/';

    /**
     * Directory processed files to be archived.
     *
     * @var string
     */
    protected $archiveDirectory = 'archive';

    /**
     * Array of field names.
     *
     * @var array
     */
    protected $fields = ['TransactionNumber', 'CustomerName', 'ReferenceNumber', 'PurchaseOrderNumber', 'ShipCarrier', 'ShipService', 'ShipBilling', 'ShipAccount', 'EarliestShipDate', 'CancelDate', 'Notes', 'ShipToName', 'ShipToCompany', 'ShipToAddress1', 'ShipToAddress2', 'ShipToCity', 'ShipToState', 'ShipToZip', 'ShipToCountry', 'ShipToPhone', 'ShipToFax', 'ShipToEmail', 'ShipToCustomerName', 'ShipToDeptNumber', 'ShipToVendorID', 'TotalCartons', 'TotalPallets', 'TotalWeight', 'TotalVolume', 'BOLNum', 'TrackingNum', 'TrailerNum', 'SealNum', 'ShipDate', 'ItemNumber', 'ItemQuantityOrdered', 'ItemQuantityShipped', 'ItemLength', 'ItemWidth', 'ItemHeight', 'ItemWeight', 'FreightPP', 'WarehouseID', 'LotNumber', 'SerialNumber', 'ExpirationDate', 'Supplier', 'Cost', 'FulfillInvShippingAndHandling', 'FulfillInvTax', 'FulfillInvDiscountCode', 'FulfillInvDiscountAmount', 'FulfillInvGiftMessage', 'SoldToName', 'SoldToCompany', 'SoldToAddress1', 'SoldToAddress2', 'SoldToCity', 'SoldToState', 'SoldToZip', 'SoldToCountry', 'SoldToPhone', 'SoldToFax', 'SoldToEmail', 'SoldToCustomerID', 'SoldToDeptNumber', 'FulfillInvSalePrice', 'FulfillInvDiscountPct', 'FulfillInvDiscountAmt'];

    /**
     * Results array.
     *
     * @var array
     */
    protected $results;

    /**
     * Temp file.
     *
     * @var string
     */
    protected $tempFile;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->tempFile = storage_path('app/temp/tracking_'.time().'.csv');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Checking '.$this->directory.' for files to process');

        if ($handle = opendir($this->directory)) {
            while (false !== ($file = readdir($handle))) {
                if (! is_dir($file) && $file != $this->archiveDirectory) {
                    $this->processFile($file);
                    $this->archiveFile($file);

                    $this->results['subject'] = 'Update Primary Freight Shipments - '.count($this->results['success']).' updated / '.count($this->results['failed']).' failed';
                    $this->results['file'] = $this->directory.$this->archiveDirectory.'/'.$file;

                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\UpdateThirdPartyShipments($this->results));

                    if (count($this->results['success']) > 0) {
                        Mail::to('kerrikids1526376033@in.fulfillment.stock-sync.com')->cc(['it@antrim.ifsgroup.com', 'info@babocush.com'])->send(new \App\Mail\BabocushTrackingNumbers($this->results, $this->tempFile));
                    }
                }
            }

            closedir($handle);
        }
    }

    /**
     * Read through the file and process the rows.
     *
     * @return void
     */
    protected function processFile($file)
    {
        $this->setResultsArray();

        $this->info("Processing $file");

        $rowNumber = 1;
        $data = null;

        if (($handle = fopen($this->directory.$file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, chr(9))) !== false) {
                if ($rowNumber >= 2) {
                    $this->processRow($rowNumber, $data);
                }
                $rowNumber++;
            }
            fclose($handle);
        }
    }

    /**
     * Process a row read from the uploaded file.
     *
     * @param type $rowNumber
     * @param type $data
     *
     * @return void
     */
    protected function processRow($rowNumber, $data)
    {
        // Assign field names to the data array
        $row = $this->assignFieldNames($data);

        // Pass the data back to the results summary
        $this->results['rows'][$rowNumber]['data'] = $row;

        // Row passes validation, continue
        if ($this->validateRow($rowNumber, $data, $row)) {

            // To accomodate PF sending forever changing formats
            if (is_numeric($row['PurchaseOrderNumber']) && strlen($row['PurchaseOrderNumber']) == 11) {
                $consignmentNumber = $row['PurchaseOrderNumber'];
                $shipmentReference = $row['ReferenceNumber'];
            } else {
                $consignmentNumber = $row['ReferenceNumber'];
                $shipmentReference = $row['PurchaseOrderNumber'];
            }

            $proceed = true;

            // Load the shipment record
            $shipment = \App\Shipment::where('consignment_number', $consignmentNumber)->first();

            // Shipment not found
            if (! $shipment) {
                $this->setRowFailed($rowNumber, $row, 'Consignment '.$consignmentNumber.' not recognised');
                $proceed = false;
            }

            $carrierConsignmentNumber = preg_replace('/\s+/', '', $row['TrackingNum']);

            if ($proceed) {
                $this->info("Found shipment $shipment->consignment_number. Updating with carrier consignment number: ".$carrierConsignmentNumber);

                // Update the shipment with details from 3rd party
                $shipment->carrier_consignment_number = $carrierConsignmentNumber;
                $shipment->carrier_tracking_number = $carrierConsignmentNumber;
                $shipment->ship_date = strtotime($row['ShipDate']);
                $shipment->save();

                // Set the shipment to received
                $shipment->setReceived(null, 0, false, false);

                // Create an easypost tracker
                dispatch(new \App\Jobs\CreateEasypostTracker($shipment->carrier_consignment_number, $this->identifyCarrier($shipment->carrier_consignment_number)));

                $this->setRowSucceeded($rowNumber, $row);

                $this->addToTrackingFile($shipment);
            }
        }
    }

    /**
     * Build a CSV file to send to babocush.
     *
     * @param type $shipment
     */
    protected function addToTrackingFile($shipment)
    {
        $handle = fopen($this->tempFile, 'a');

        $line = [
            $shipment->shipment_reference,
            1,
            $shipment->pieces,
            $shipment->carrier_consignment_number,
            $this->identifyCarrier($shipment->carrier_consignment_number),
        ];

        fputcsv($handle, $line);
        fclose($handle);
    }

    /**
     * Read one line at a time and create an array of field names and values.
     *
     * @param type $data
     *
     * @return void
     */
    protected function assignFieldNames($data)
    {
        $row = [];

        $i = 0;
        foreach ($this->fields as $field) {
            $row[$field] = (isset($data[$i])) ? trim($data[$i]) : null;
            $i++;
        }

        return $row;
    }

    /**
     * Basic row validation. Returns true/false and sets row to failed if validation fails.
     *
     * @param type $rowNumber
     * @param type $data
     * @param type $row
     * @return bool
     */
    protected function validateRow($rowNumber, $data, $row)
    {
        // First check for correct number of fields
        if (count($data) != count($this->fields)) {
            $this->setRowFailed($rowNumber, $row, 'Invalid number of fields detected. Detected '.count($data).' fields. '.count($this->fields).' required');

            return false;
        }

        $rules = [
            'ReferenceNumber' => 'required',
            'TrackingNum' => 'required|string|min:6',
            'ShipDate' => 'required|string',
        ];

        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $this->setRowFailed($rowNumber, $row, $errors->all());

            return false;
        }

        return true;
    }

    /**
     * Declare the results array.
     *
     * @return void
     */
    protected function setResultsArray()
    {
        $this->results['success'] = [];
        $this->results['failed'] = [];
        $this->results['rows'] = [];
    }

    /**
     * Add a row to failed results.
     *
     * @param type $rowNumber
     * @param type $errors
     *
     * @return void
     */
    protected function setRowFailed($rowNumber, $row, $errors)
    {
        if (is_string($errors)) {
            $this->error("Row $rowNumber: $errors");
        } else {
            foreach ($errors as $error) {
                $this->error("Row $rowNumber: $error");
            }
        }

        $this->results['failed'][$rowNumber] = $row;
        $this->results['failed'][$rowNumber]['errors'] = $errors;
    }

    /**
     * Add a row to the successful results.
     *
     * @param type $rowNumber
     * @param type $result
     *
     * @return void
     */
    protected function setRowSucceeded($rowNumber, $row)
    {
        $this->results['success'][$rowNumber] = $row;
    }

    /**
     * Move file to archive directory.
     *
     * @param string $file
     * @return bool
     */
    protected function archiveFile($file)
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

    /**
     * Get easypost carrier from tracking number.
     *
     * @param type $carrierConsignmentNumber
     * @return string
     */
    protected function identifyCarrier($carrierConsignmentNumber)
    {
        if (substr(strtoupper($carrierConsignmentNumber), 0, 2) == '1Z') {
            return 'UPS';
        }

        if (substr($carrierConsignmentNumber, 0, 1) == 7 && strlen($carrierConsignmentNumber) == 12) {
            return 'FedEx';
        }

        return 'USPS';
    }
}
