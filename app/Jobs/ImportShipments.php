<?php

namespace App\Jobs;

use App\Models\Carrier;
use App\Models\CarrierAPI\Facades\CarrierAPI;
use App\Models\Country;
use App\Models\Department;
use App\Models\Postcode;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Validator;

class ImportShipments implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 999;
    protected $path;
    protected $importConfig;
    protected $user;
    protected $company;
    protected $fields;
    protected $row;
    protected $results;
    protected $source;
    protected $errors;
    protected $userPreferences;

    /**
     * Create a new job instance.
     *
     * @param string $path
     * @param int $importConfigId
     * @param User $user
     *
     * @return void
     */
    public function __construct($path, $importConfigId, User $user)
    {
        $this->path = $path;
        $this->importConfig = \App\Models\ImportConfig::find($importConfigId);
        $this->user = $user;
        $this->company = $this->importConfig->company;
        $this->userPreferences = $user->getPreferences($this->company->id, $this->importConfig->mode_id, true);
        $this->errors = [];

        // Extract string identifier from the filename
        $source = pathinfo($path);
        $this->source = substr($source['filename'], 9, 12);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setFields();

        $this->setResultsArray();

        $this->processFile();

        $this->exportResultsToCsvFiles();

        $this->results['files'][] = $this->path;

        $this->results['commercial_invoice_count'] = $this->getCommercialInvoiceCount();

        $this->setSubject();

        Mail::to($this->user->email)->cc($this->importConfig->cc_import_results_email ?: [])->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\ShipmentUploadResults($this->results));
    }

    /**
     * Set fields as defined in the import config table.
     *
     * @return void
     */
    private function setFields()
    {
        for ($i = 0; $i < $this->importConfig->numberOfColumns; $i++) {
            $field = "column$i";
            if ($this->importConfig->$field) {
                $this->fields[] = $this->importConfig->$field;
            }
        }
    }

    /**
     * Declare the results array.
     *
     * @return void
     */
    private function setResultsArray()
    {
        $this->results['user']['id'] = $this->user->id;
        $this->results['user']['name'] = $this->user->name;
        $this->results['source'] = $this->source;
        $this->results['success'] = [];
        $this->results['failed'] = [];
        $this->results['rows'] = [];
        $this->results['commercial_invoice_count'] = 0;
    }

    /**
     * Read through the file and insert a record for each valid row.
     *
     * @return void
     */
    private function processFile()
    {
        $rowNumber = 1;
        if (($handle = fopen($this->path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, $this->getDelimiter())) !== false) {
                if ($rowNumber >= $this->importConfig->start_row) {
                    $this->processRow($rowNumber, $data);
                }
                $rowNumber++;
            }
            fclose($handle);
        }
    }

    private function getDelimiter()
    {
        switch ($this->importConfig->delim) {
            case 'tab':
                return chr(8);
            default:
                return ',';
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
    private function processRow($rowNumber, $data)
    {
        $this->row = [];

        // Assign field names to the data array
        $this->assignFieldNames($data);

        // Pass the data back to the results summary
        $this->results['rows'][$rowNumber]['data'] = $this->row;

        // Invalid number of fields, return false
        if (count($data) != count($this->fields)) {
            $this->setRowFailed($rowNumber, [0 => 'Invalid number of fields detected. Detected '.count($data).' fields. '.count($this->fields).' required']);

            return false;
        }

        // Attempt to supply values for any information not provided
        $this->completeEmptyFields();

        if (! $this->getService()) {
            $this->setRowFailed($rowNumber, [0 => 'Unable to determine service']);

            return false;
        }

        // Count the number of shipments raised this month for kukoon economy
        if ($this->company->id == 808) {
            $count = \App\Models\Shipment::whereCompanyId(808)->whereBetween('ship_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->whereNotIn('status_id', [1, 7])->count();

            if ($count >= 500) {
                $this->setRowFailed($rowNumber, [0 => 'Exceeded monthly shipment allowance for '.$this->company->company_name]);

                return false;
            }
        }

        // Some validation
        $rules = [
            'recipient_name' => 'required_without:recipient_company_name|min:1|max:35',
            'recipient_company_name' => 'required_without:recipient_name|min:1|max:35',
            'recipient_address1' => 'required|min:1|max:35',
            'recipient_address2' => 'sometimes|min:1|max:35',
            'recipient_address3' => 'sometimes|min:1|max:35',
            'recipient_city' => 'required|min:1|max:30',
            'recipient_state' => 'sometimes|min:1|max:30',
            'recipient_telephone' => 'required|string|min:8|max:18',
            'recipient_country_code' => 'required|exists:countries,country_code',
            'pieces' => 'required|min:1|max:99',
            'weight' => 'required|min:0.1|max:19999',
            'shipment_reference' => 'required|string',
            'service_code' => 'sometimes|exists:services,code',
            'product_quantity' => 'sometimes|min:1|max:999999',
            'customs_value' => 'sometimes|min:1|max:9999999',
        ];

        $validator = Validator::make($this->row, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $this->errors[] = $message;
            }
        }

        $country = null;
        $country = Country::where('country_code', $this->row['recipient_country_code'])->first();
        if (isset($country->postal_validation) && $country->postal_validation > '') {
            $validator = Validator::make($this->row, ['recipient_postcode' => 'regex:'.$country->postal_validation]);

            if ($validator->fails()) {
                $error = 'Invalid Postcode format.';
                if (isset($country->postcode_example) && $country->postcode_example > '') {
                    $error .= ' Example : '.$country->postcode_example;
                }
                $this->errors[] = $error;
            }
        }

        if ($this->errors != []) {
            $this->setRowFailed($rowNumber, $this->errors);
            $this->errors = [];

            return false;
        }

        if ($this->shipmentExists()) {
            $this->setRowFailed($rowNumber, [0 => 'Shipment already exists']);

            return false;
        }

        // Call the api to create shipment
        $result = CarrierAPI::createShipment($this->row);

        // If no errors, return false and add to error array
        if (! isset($result['errors']) || $result['errors'] == []) {
            $this->setRowSucceeded($rowNumber, $result);

            return true;
        }

        $this->setRowFailed($rowNumber, $result['errors']);

        return false;
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
            switch ($field) {
                case 'sender_postcode':
                case 'recipient_postcode':
                    $this->row[$field] = (isset($data[$i])) ? trim(strtoupper(convertToUTF8($data[$i]))) : null;
                    break;

                default:
                    $this->row[$field] = (isset($data[$i])) ? trim(convertToUTF8($data[$i])) : null;
                    break;
            }
            $i++;
        }
    }

    /**
     * Add a row to failed results.
     *
     * @param type $rowNumber
     * @param type $errors
     *
     * @return void
     */
    private function setRowFailed($rowNumber, $errors)
    {
        $this->results['failed'][$rowNumber] = $this->row;
        $this->results['failed'][$rowNumber]['errors'] = $errors;
    }

    /**
     * Attempt to supply values for any information not provided.
     */
    private function completeEmptyFields()
    {
        // Sender details undefined so use company details
        $this->defaultSenderDetails();

        // Add Misc details
        $this->row['source'] = $this->source;
        $this->row['user_id'] = $this->user->id;
        $this->row['company_id'] = $this->company->id;
        $this->row['mode_id'] = $this->importConfig->mode_id;
        $this->row['mode'] = $this->importConfig->mode->name;
        $this->row['dims_uom'] = $this->company->localisation->dims_uom;
        $this->row['weight_uom'] = $this->company->localisation->weight_uom;
        $this->row['date_format'] = $this->company->localisation->date_format;
        $this->row['customs_value_currency_code'] = $this->company->localisation->currency_code;
        $this->row['label_size'] = $this->company->localisation->document_size;
        $this->row['ship_reason'] = 'sold';
        $this->row['terms_of_sale'] = (empty($this->row['terms_of_sale'])) ? $this->importConfig->default_terms : $this->row['terms_of_sale'];
        $this->row['bill_shipping'] = (empty($this->row['bill_shipping'])) ? 'sender' : $this->row['bill_shipping'];
        $this->row['bill_tax_duty'] = (empty($this->row['bill_tax_duty'])) ? whoPaysDuty($this->row['terms_of_sale']) : $this->row['bill_tax_duty'];
        $this->row['customs_value'] = $this->getCustomsValue();
        $this->row['goods_description'] = (empty($this->row['goods_description'])) ? $this->importConfig->default_goods_description : $this->row['goods_description'];
        $this->row['packaging_code'] = (empty($this->row['packaging_code'])) ? $this->company->getPackagingTypes(1)->first()->code : $this->row['packaging_code'];
        $this->row['documents_flag'] = (empty($this->row['documents_flag'])) ? false : $this->row['documents_flag'];
        $this->row['pieces'] = (empty($this->row['pieces']) || $this->row['pieces'] < 1) ? $this->importConfig->default_pieces : $this->row['pieces'];
        $this->row['weight'] = (empty($this->row['weight']) || $this->row['weight'] <= 0) ? $this->importConfig->default_weight : $this->row['weight'];
        $this->row['service_code'] = (empty($this->row['service_code'])) ? $this->importConfig->default_service : $this->row['service_code'];
        $this->row['recipient_name'] = (empty($this->row['recipient_name'])) ? ' ' : $this->row['recipient_name'];
        $this->row['recipient_address1'] = (empty($this->row['recipient_address1'])) ? ' ' : $this->row['recipient_address1'];
        $this->row['recipient_address2'] = (empty($this->row['recipient_address2'])) ? ' ' : $this->row['recipient_address2'];
        $this->row['recipient_address3'] = (empty($this->row['recipient_address3'])) ? ' ' : $this->row['recipient_address3'];
        $this->row['recipient_country_code'] = getCountryCode($this->row['recipient_country_code']);
        $this->row['recipient_email'] = (empty($this->row['recipient_email'])) ? $this->importConfig->default_recipient_email : $this->row['recipient_email'];
        $this->row['recipient_telephone'] = (empty($this->row['recipient_telephone'])) ? $this->importConfig->default_recipient_telephone : $this->row['recipient_telephone'];
        $this->row['country_of_destination'] = $this->row['recipient_country_code'];
        $this->row['department_id'] = Department::where('code', identifyDepartment($this->row))->first()->id;
        $this->row['alerts'] = (isset($this->userPreferences['alerts'])) ? $this->userPreferences['alerts'] : [];
        $this->row['other_email'] = (isset($this->userPreferences['other_email'])) ? $this->userPreferences['other_email'] : '';
        $this->row['product_quantity'] = (empty($this->row['product_quantity'])) ? 1 : $this->row['product_quantity'];

        // If Recipient Type not specified then Guess
        if (empty($this->row['recipient_type'])) {
            if (empty($this->row['recipient_company_name'])) {
                $this->row['recipient_type'] = 'r';
            } else {
                $this->row['recipient_type'] = 'c';
            }
        }

        // Check for collection date
        if (empty($this->row['collection_date'])) {
            $pickUpTime = new Postcode();

            // Get first available pickupdate in Y-m-d format
            $cutOffDate = $pickUpTime->getPickUpDate($this->row['sender_country_code'], $this->row['sender_postcode'], $this->company->localisation->time_zone);

            // Convert to users format
            $this->row['collection_date'] = Carbon::createFromformat('Y-m-d', $cutOffDate)->format(getDateFormat($this->row['date_format']));
        }

        $this->setPackagesDetails();
        $this->setContentsDetails();
    }

    /**
     * Complete Sender details if not supplied
     * Based on Company and User details.
     */
    private function defaultSenderDetails()
    {
        $this->row['sender_type'] = 'c';
        $this->row['sender_name'] = $this->user->name;
        $this->row['sender_company_name'] = $this->company->company_name;
        $this->row['sender_address1'] = $this->company->address1;
        $this->row['sender_address2'] = $this->company->address2;
        $this->row['sender_address3'] = $this->company->address3;
        $this->row['sender_city'] = $this->company->city;
        $this->row['sender_state'] = $this->company->state;
        $this->row['sender_postcode'] = $this->company->postcode;
        $this->row['sender_country_code'] = $this->company->country_code;
        $this->row['sender_telephone'] = $this->company->telephone;
        $this->row['sender_email'] = ($this->company->email) ? $this->company->email : $this->user->email;
    }

    /**
     * Builds package array since Package details cannot be set
     * Using a one line per shipment import.
     */
    private function setPackagesDetails()
    {
        // Get details of this Packaging Type if it exists
        $packaging = $this->company->getPackagingTypes($this->importConfig->mode_id)->where('code', $this->row['packaging_code'])->first();

        /*
         * ************************************
         * Set package Weights
         * ************************************
         */
        // If weight not defined use package default
        if (! isset($this->row['weight']) || $this->row['weight'] <= 0) {
            if ($packaging) {
                $this->row['weight'] = $packaging->weight;
            }
        }

        // Set weight for each package
        $this->setPackageWeight($this->row['weight']);

        /*
         * ***********************************
         * Set Package Dims
         * ***********************************
         */
        // Has User supplied Package Dims
        if ($this->packageDimsSupplied($this->row)) {

            // Use Supplied Dims
            $this->setPackageDims($this->row);
        } else {

            // If Default dims defined for package type
            if ($packaging && $this->packageDimsSupplied($packaging->toArray())) {

                // Use Default Package Dims
                $this->calcDimsUsingPackaging($packaging);
            } else {

                // If supplied, use Total Shipment Volumetric Weight to apportion across packages
                if (isset($this->row['volumetric_weight']) && $this->row['volumetric_weight'] > 0) {
                    $this->calcDimsUsingWeight($this->row['volumetric_weight']);
                } else {

                    // If supplied, use Total Shipment Actual Weight to apportion across packages
                    if (isset($this->row['weight']) && $this->row['weight'] > 0) {
                        $this->calcDimsUsingWeight($this->row['weight']);
                    } else {

                        // Packaging type not defined
                        $this->errors[] = 'Unable to calculate volumetric weight';
                    }
                }
            }
        }
    }

    private function setPackageWeight($pkgWeight = 0)
    {
        $total_weight = 0;
        for ($i = 0; $i < $this->row['pieces']; $i++) {
            if ($pkgWeight > 0) {

                // Set package weight to PackageType weight and calc Shipment Total Weight
                $this->row['packages'][$i]['weight'] = $pkgWeight;
                $this->row['weight'] = $pkgWeight * $this->row['pieces'];
            } else {
                if ($this->row['pieces'] == 1) {

                    // If only one piece set to Shipment Weight
                    $this->row['packages'][$i]['weight'] = $this->row['weight'];
                } else {

                    // ensure total of individual package weight equals the record['weight']
                    $calcWeight = ceil(round(($this->row['weight'] - $total_weight) / ($this->row['pieces'] - $i), 2) * 2) / 2;
                    if ($calcWeight < .5) {
                        $calcWeight = .5;
                    }
                    $total_weight += $calcWeight;
                    $this->row['packages'][$i]['weight'] = $calcWeight;
                }
            }
        }
    }

    /*
     * Get delimiter defined in the import config.
     *
     * @return string
     */

    /**
     * Check to see if dims supplied in feed if so
     * then set package dims.
     *
     * @return bool
     */
    private function packageDimsSupplied($data)
    {

        // Check to see if user has supplied dims
        if (isset($data['length']) && isset($data['width']) && isset($data['height'])) {
            if ($data['length'] > 0 && $data['width'] > 0 && $data['height'] > 0) {
                return true;
            }
        }

        return false;
    }

    private function setPackageDims($data)
    {
        if (isset($data['length']) && isset($data['width']) && isset($data['height'])) {
            if ($data['length'] > 0 && $data['width'] > 0 && $data['height'] > 0) {
                for ($i = 0; $i < $data['pieces']; $i++) {
                    $this->setDimsForPackage($i, $data['length'], $data['width'], $data['height']);
                }
            }
        }
    }

    //************************************************************************************************************************************************************************************************** //
    //************************************************************************************************************************************************************************************************** //
    //************************************************************************************************************************************************************************************************** //

    private function setDimsForPackage($i, $length, $width, $height)
    {
        $this->row['packages'][$i]['packaging_code'] = $this->row['packaging_code'];
        $this->row['packages'][$i]['length'] = $length;
        $this->row['packages'][$i]['width'] = $width;
        $this->row['packages'][$i]['height'] = $height;
    }

    private function calcDimsUsingPackaging($packaging)
    {
        // Do this for each Package
        for ($i = 0; $i < $this->row['pieces']; $i++) {

            // If Dims supplied for Packaging type use them
            if ($packaging['length'] > 0 && $packaging['width'] > 0 && $packaging['height'] > 0) {

                // Set dims to PackageType dims
                $this->setDimsForPackage($i, $packaging['length'], $packaging['width'], $packaging['height']);
            }
        }
    }

    /**
     * Sets package weight to supplied package weight or
     * Guesses it from volumetric or actual weight.
     *
     * @param type $packagingWeight
     */
    private function calcDimsUsingWeight($totalWeight)
    {
        $packageWeight = $totalWeight / $this->row['pieces'];

        // Do this for each Package
        for ($i = 0; $i < $this->row['pieces']; $i++) {
            $length = 0;
            $width = 0;
            $height = 0;

            // Calc dims based on actual weight - assume a cube - use dims of first carrier service found
            $service = Service::where('code', $this->row['service_code'])->first();

            if ($service) {
                $volDivisor = Service::where('code', $this->row['service_code'])->first()->volumetric_divisor;
            } else {
                $volDivisor = Service::where('code', 'uk48')->first()->volumetric_divisor;
            }

            $length = floor(pow($packageWeight * $volDivisor, 1 / 3)) * 1.2;
            $width = floor(pow($packageWeight * $volDivisor, 1 / 3));
            $height = floor(($packageWeight * $volDivisor) / ($length * $width));

            $this->setDimsForPackage($i, $length, $width, $height);
        }
    }

    /**
     * Builds Contents Array.
     */
    private function setContentsDetails()
    {
        $commodity = false;

        // If this section is not required then return
        if (customsEntryRequired($this->row['sender_country_code'], $this->row['recipient_country_code'])) {
            if (empty($this->row['product_code'])) {
                $this->errors[] = 'Product code required for this destination';

                return;
            }

            if (empty($this->row['product_quantity'])) {
                $this->errors[] = 'Product quantity required for this destination';

                return;
            }
        }

        if (! empty($this->row['product_code'])) {
            $commodity = \App\Models\Commodity::whereProductCode($this->row['product_code'])->whereCompanyId($this->company->id)->first();
        }

        if ($commodity) {
            // Only one content line allowed in this interface
            $this->row['contents'][0]['package_index'] = '1';
            $this->row['contents'][0]['description'] = $commodity->description;
            $this->row['contents'][0]['manufacturer'] = $commodity->manufacturer;
            $this->row['contents'][0]['product_code'] = $commodity->product_code;
            $this->row['contents'][0]['commodity_code'] = $commodity->commodity_code;
            $this->row['contents'][0]['harmonized_code'] = $commodity->harmonized_code;
            $this->row['contents'][0]['country_of_manufacture'] = $commodity->country_of_manufacture;
            $this->row['contents'][0]['quantity'] = $this->row['product_quantity'];
            $this->row['contents'][0]['uom'] = $commodity->uom;
            $this->row['contents'][0]['unit_value'] = round($this->row['customs_value'] / $this->row['product_quantity'], 2);
            $this->row['contents'][0]['currency_code'] = $commodity->currency_code;
            $this->row['contents'][0]['unit_weight'] = round($this->row['weight'] / $this->row['product_quantity'], 2);
            $this->row['contents'][0]['weight_uom'] = $commodity->weight_uom;
        }

        if (! $commodity && customsEntryRequired($this->row['sender_country_code'], $this->row['recipient_country_code'])) {
            $this->errors[] = 'Product code not found';
        }
    }

    /**
     * Get service.
     *
     * @return bool
     */
    private function getService()
    {
        $service = $this->chooseService();

        if ($service) {
            $this->row['service_id'] = $service['id'];
            $this->row['service_code'] = strtoupper($service['code']);
            $this->row['carrier_id'] = $service['carrier_id'];
            $this->row['carrier_code'] = Carrier::find($this->row['carrier_id'])->code;

            return true;
        }

        return false;
    }

    /**
     * Choose the appropriate service based upon cost/price.
     *
     * @return mixed
     */
    private function chooseService()
    {
        $availableServices = CarrierAPI::getAvailableServices($this->row);

        $carrierChoice = strtolower($this->company->carrier_choice);

        if (! is_array($availableServices)) {
            return false;
        }

        switch ($carrierChoice) {

            case 'cost':
            case 'price':
                $services = CarrierAPI::getCheapestService($availableServices, $carrierChoice);
                break;

            default:
                $services = $availableServices;
                break;
        }

        if ($services) {
            return reset($services);
        }

        return false;
    }

    /**
     * Check to see if a shipment record has already been created.
     *
     * @return bool
     */
    private function shipmentExists()
    {
        $shipment = \App\Models\Shipment::whereShipmentReference(strtoupper($this->row['shipment_reference']))
            ->whereRecipientPostcode($this->row['recipient_postcode'])
            ->wherePieces($this->row['pieces'])
            ->whereCompanyId($this->company->id)
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->first();

        if (isset($shipment->id)) {
            return true;
        }

        return false;
    }

    /**
     * Add a row to the successful results.
     *
     * @param type $rowNumber
     * @param type $result
     *
     * @return void
     */
    private function setRowSucceeded($rowNumber, $result)
    {
        $this->results['success'][$rowNumber] = $this->row;
        $this->results['success'][$rowNumber]['consignment_number'] = $result['ifs_consignment_number'];
        $this->results['success'][$rowNumber]['carrier_consignment_number'] = $result['consignment_number'];
        $this->results['success'][$rowNumber]['carrier'] = strtoupper($result['carrier']);
        $this->results['success'][$rowNumber]['tracking_url'] = $result['tracking_url'];
    }

    /**
     * Export the results array into csv files. Rows that were successfully imported are inserted
     * to success.csv and failed rows into failed.csv.
     *
     * @return void
     */
    private function exportResultsToCsvFiles()
    {
        if (count($this->results['success']) > 0) {
            $this->exportRowsToCsv($this->results['success'], 'success_'.$this->source);
        }
        if (count($this->results['failed']) > 0) {
            $this->exportRowsToCsv($this->results['failed'], 'failed_'.$this->source);
        }
    }

    /**
     * Create a csv file from an array of rows.
     *
     * @param array $rows
     * @param string $fileName
     *
     * @return void
     */
    private function exportRowsToCsv($rows, $fileName)
    {
        // Build an array that will be written to csv
        foreach ($rows as $key => $row) {
            foreach ($this->fields as $field) {
                $data[$key][$field] = (isset($row[$field])) ? $row[$field] : null;
            }

            if (isset($row['consignment_number'])) {
                $data[$key]['consignment_number'] = $row['consignment_number'];
            }

            if (isset($row['carrier_consignment_number'])) {
                $data[$key]['carrier_consignment_number'] = $row['carrier_consignment_number'];
            }

            if (isset($row['carrier'])) {
                $data[$key]['carrier'] = $row['carrier'];
            }

            if (isset($row['tracking_url'])) {
                $data[$key]['tracking_url'] = $row['tracking_url'];
            }

            if (isset($row['errors'])) {
                unset($data[$key]['errors']);
            }
        }

        $result = writeCsv(storage_path().'/app/temp/'.$fileName.'.csv', $data);

        // Add the filename to the results array
        $this->results['files'][] = storage_path().'/app/temp/'.$fileName.'.csv';
    }

    /**
     * Count the number of commercial invoices available.
     *
     * @return int
     */
    private function getCommercialInvoiceCount()
    {
        return \App\Models\Shipment::whereSource($this->source)->notEu()->notDomestic()->notUkDomestic()->where('service_id', '!=', 29)->count();
    }

    /**
     * Get the subject of mail sent to user.
     *
     * @return string
     */
    private function setSubject()
    {
        $this->results['subject'] = 'Shipment Upload ('.$this->importConfig->mode->label.') - '.$this->importConfig->company->company_name.' - '.count($this->results['success']).' created / '.count($this->results['failed']).' failed';
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed($exception)
    {
        // Mail exception to IT
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\JobFailed("Shipment Import ($this->source)", $exception, $this->path));

        // Mail end user to notify thenm of an issue
        Mail::to($this->user->email)->cc($this->importConfig->cc_import_results_email ?: [])->bcc('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Shipment Upload Failed', 'There was a problem with the file uploaded. Please check the values in the CSV file and try again.', $this->path));
    }

    /**
     * Ensure numeric customs value.
     *
     * @param $customsValue
     * @return float
     */
    protected function getCustomsValue()
    {
        $customsValue = (empty($this->row['customs_value']) || $this->row['customs_value'] < 1 || $this->row['customs_value'] == '') ? $this->importConfig->default_customs_value : trim($this->row['customs_value']);

        if (! is_numeric($customsValue)) {
            return (float) str_replace(' ', '.', $customsValue);
        }

        return $customsValue;
    }
}
