<?php

namespace App\Console\Commands;

use App\Vmi\VvOrders;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessVendorvillageOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:process-vendorvillage-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create shipments from Vendorvillage orders that have a "courier_dispatch_status" of (1)';

    /**
     *  Array of errors encountered.
     *
     * @var type
     */
    protected $errors = [];

    /**
     * Company dispatch settings.
     *
     * @var type
     */
    protected $dispatchSettings;

    /**
     * Array of base 64 labels.
     *
     * @var array
     */
    protected $labels = [];

    /**
     * FTP host for print uploads.
     *
     * @var type
     */
    protected $ftpServer = 'vmi.ifsgroup.com';

    /**
     * @var type
     */
    protected $ftpUser = 'vmiprint1';

    /**
     * @var type
     */
    protected $ftpPassword = 'tLniwtbP7';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client(['base_uri' => 'https://ship.ifsgroup.com/api/v2/', 'headers' => [
                'Authorization' => 'Bearer amk2ypk8hz3ebe7cc0olbfspuf2oegpicxv2ro6m',
        ]]);

        // Get the enabled shipment uploads
        $orders = VvOrders::whereCourierDispatchStatus(1)->orderBy('buyer_id')->get();

        // Loop through each of the uploads
        foreach ($orders as $order):

            // Load the associated company record
            $company = $order->getCompany();

        if (! $company) {
            $this->setFailed('Courier company id not defined for '.$order->company->company_name.' within Vendorvillage. Order '.$order->order_number.' not processed', $order);
            continue;
        }

        // Get the dispatch settings from VV
        $this->dispatchSettings = \App\Vmi\Company::find($order->buyer_id)->courierDispatch;

        if (! $this->dispatchSettings) {
            $this->setFailed('Courier dispatch settings not defined for '.$company->company_name.' within Vendorvillage. Order '.$order->order_number.' not processed', $order);
            continue;
        }

        try {
            $response = $client->post('shipments', ['json' => $this->buildRequest($order, $company)]);
            $result = json_decode($response->getBody()->getContents(), true);
            $order->setToDispatched($result['data']);

            if ($this->dispatchSettings->printer_id) {
                $this->labels[$order->order_number] = $result['data']['label_base64'][0]['base64'];
            }
        } catch (GuzzleException $exception) {
            $this->setFailed($company->company_name.' order '.$order->order_number.' not processed. ERROR: '.$exception->getResponse()->getBody()->getContents(), $order);
        }

        endforeach;

        // FTP the PDFs to vendorvillage print spooler
        $this->ftpPdfToVendorvillage();

        if (count($this->errors) > 0) {
            Mail::to('vmi@kilroot.ifsgroup.com')->cc(['awady@kilroot.ifsgroup.com'])->bcc(['it@antrim.ifsgroup.com'])->send(new \App\Mail\GenericError('Failed to create courier shipments - '.count($this->errors).' failed', $this->errors, false, 'Operator Action Required', 'It was not possible to auto generate courier shipments for the Vendorvillage orders detailed below. Please dispatch these orders ASAP.'));
        }

        $this->info('Finished processing shipment uploads');
    }

    /**
     * Build request.
     *
     * @param type $order
     * @return array
     */
    protected function buildRequest($order, $company)
    {
        $pieces = $this->getPieces($order->stockItems);
        $weight = $this->getWeight($order->stockItems, $pieces);

        return [
            'transaction_id' => 'vendorvillage '.$order->order_number,
            'company_code' => $company->company_code,
            'pieces' => $pieces,
            'weight' => $weight,
            'weight_uom' => 'KG',
            'dimension_uom' => 'CM',
            'shipment_reference' => $order->order_number,
            'country_of_destination' => $order->country_code,
            'customs_value' => $this->getCustomsValue($order->stockItems),
            'currency_code' => 'GBP',
            'ship_reason' => 'SOLD',
            'bill_shipping' => 'SHIPPER',
            'bill_tax_duty' => 'RECIPIENT',
            'bill_shipping_account' => null,
            'bill_tax_duty_account' => null,
            'shipper' => [
                'contact' => 'Despatch',
                'company_name' => $company->company_name,
                'telephone' => $company->depot->telephone,
                'email' => $company->depot->email,
                'address1' => 'c/o '.$company->depot->name,
                'address2' => $company->depot->address1,
                'address3' => $company->depot->address2,
                'city' => $company->depot->city,
                'state' => $company->depot->state,
                'postcode' => $company->depot->postcode,
                'country_code' => $company->depot->country_code,
                'type' => 'C',
            ],
            'recipient' => [
                'contact' => $order->contact_name,
                'company_name' => $order->company_name,
                'telephone' => $order->telephone,
                'email' => $order->email,
                'address1' => $order->address1,
                'address2' => $order->address2,
                'address3' => $order->address3,
                'city' => $order->city,
                'state' => $order->county,
                'postcode' => $order->postcode,
                'country_code' => $order->country_code,
                'type' => ($order->company_name) ? 'C' : 'R',
            ],
            'packages' => $this->getPackages($pieces, $order->stockItems),
            'documents_flag' => 'N',
            'goods_description' => $this->dispatchSettings->goods_description,
            'commodities' => $this->getCommodities($order->stockItems),
            'terms_of_sale' => 'DAP',
        ];
    }

    /**
     * Get the number of pieces.
     *
     * @return type
     */
    protected function getPieces($stockItems)
    {
        if ($this->dispatchSettings->pieces_defaulted) {
            return $this->dispatchSettings->pieces;
        }

        return $stockItems->sum('actual_quantity');
    }

    /**
     * Get the shipment weight.
     *
     * @return type
     */
    protected function getWeight($stockItems, $pieces)
    {
        if ($this->dispatchSettings->weight_defaulted) {
            return $this->dispatchSettings->weight * $pieces;
        }

        $weight = 0;

        foreach ($stockItems as $item) {
            $weight += $item->stock->weight * $item->actual_quantity;
        }

        return $weight;
    }

    /**
     * Get the customs value.
     *
     * @return type
     */
    protected function getCustomsValue($stockItems)
    {
        $customsValue = 0;

        foreach ($stockItems as $item) {
            $customsValue += $item->stock->default_unit_cost;
        }

        return $customsValue;
    }

    /**
     * @return string
     */
    protected function getPackages($pieces, $stockItems)
    {
        $packages = [];

        if ($this->dispatchSettings->pieces_defaulted) {
            for ($i = 0; $i < $pieces; $i++) {
                $packages[] = [
                    'packaging_code' => 'CTN',
                    'length' => $this->dispatchSettings->length,
                    'width' => $this->dispatchSettings->width,
                    'height' => $this->dispatchSettings->height,
                    'weight' => $this->dispatchSettings->weight,
                ];
            }

            return $packages;
        }

        // One piece per item
        foreach ($stockItems as $item) {
            for ($i = 0; $i < $item->actual_quantity; $i++) {
                $packages[] = [
                    'packaging_code' => 'CTN',
                    'length' => ($this->dispatchSettings->dimensions_defaulted) ? $this->dispatchSettings->length : $item->stock->length,
                    'width' => ($this->dispatchSettings->dimensions_defaulted) ? $this->dispatchSettings->width : $item->stock->width,
                    'height' => ($this->dispatchSettings->dimensions_defaulted) ? $this->dispatchSettings->height : $item->stock->height,
                    'weight' => ($this->dispatchSettings->weight_calculated) ? $this->dispatchSettings->weight : $item->stock->weight,
                ];
            }
        }

        return $packages;
    }

    /**
     * @param type $stockItems
     * @return type
     */
    protected function getCommodities($stockItems)
    {
        $commodities = [];

        foreach ($stockItems as $key => $item) {
            $commodities[] = [
                'package_index' => $key + 1,
                'quantity' => $item->actual_quantity,
                'description' => $item->stock->description,
                'manufacturer' => $item->stock->manufacturer,
                'country_of_manufacture' => $item->stock->country_of_manufacture,
                'unit_value' => $item->stock->default_unit_cost,
                'currency_code' => $item->stock->default_currency_code,
                'unit_weight' => $item->stock->weight,
                'commodity_code' => $item->stock->commodity_code,
                'part_number' => $item->stock->part_number,
                'uom' => 'EA',
                'weight_uom' => 'KG',
            ];
        }

        return $commodities;
    }

    /**
     * Append to the errors array and update the order status to (3) FAILED.
     *
     * @param type $message
     * @param type $order
     */
    protected function setFailed($message, $order)
    {
        $this->error($message);
        $this->errors[] = $message;

        $order->status_code = 'DISP';
        $order->courier_dispatch_status = 3;
        $order->save();
    }

    /**
     * Create PDFs and upload them to vendorvillage for auto printing.
     */
    protected function ftpPdfToVendorvillage()
    {
        $uploads = [];

        if (count($this->labels) > 0) {
            $this->line('Uploading '.count($this->labels).' labels');

            foreach ($this->labels as $orderNumber => $label) {
                $path = storage_path('app/temp/'.$orderNumber.'.pdf');
                $file = base64_decode($label);
                file_put_contents($path, $file);

                if (file_exists($path)) {
                    $uploads[$orderNumber] = $path;
                } else {
                    $this->error('File not found: '.$path);
                }
            }
        }

        if (count($uploads) > 0) {
            $this->line(count($uploads).' files to upload to printer');

            // set up basic connection
            $conn_id = ftp_connect($this->ftpServer);

            // login with username and password
            $login_result = ftp_login($conn_id, $this->ftpUser, $this->ftpPassword);

            // Turn on passive mode
            ftp_pasv($conn_id, true);

            foreach ($uploads as $orderNumber => $file) {
                ftp_put($conn_id, 'printer-'.$this->dispatchSettings->printer_id.'/'.$orderNumber.'.pdf', $file, FTP_BINARY);
            }

            ftp_close($conn_id);
        }
    }
}
