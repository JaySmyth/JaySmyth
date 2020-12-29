<?php

namespace App\CarrierAPI;

use App;
use App\Mail\GenericError;
use App\Models\Shipment;
use App\Pricing\Facades\Pricing;
use DB;
use Exception;
use Illuminate\Support\Facades\Mail;
use TCPDI;

/**
 * Description of CarrierWebServices.
 *
 * @author gmcbroom
 */
class CarrierAPI
{
    private $carrier;
    private $consignment;
    private $companyServices;
    private $mode;

    /**
     * Accepts Shipment details and returns all services
     * that are available and appropriate for the shipment.
     *
     * @param  type  $shipment
     * @param  string  $mode  Used by APIController to overide mode
     *
     * @return array of available services
     */
    public function getAvailableServices($data, $mode = '')
    {
        $this->setEnvironment($mode);
        $this->consignment = new Consignment($data);
        $this->consignment->checkAddresses();
        $this->setCompanyServices();

        return $this->companyServices->getAvailableServices();
    }

    /**
     * @param  string  $mode
     */
    public function setEnvironment($mode = '')
    {
        // if mode is defined then use it
        $env_mode = ($mode > '') ? $mode : App::environment();
        try {
            // If Environment variable set to Production, then change mode
            switch (strtoupper($env_mode)) {
                case 'PRODUCTION':
                case 'TESTING':
                    $this->mode = 'production';
                    break;

                case 'LOCAL':
                case 'TEST':
                    $this->mode = 'test';
                    break;

                default:
                    dd('Unknown Mode : *'.$env_mode.'*');
                    break;
            }
        } catch (Exception $e) {
            Mail::to('it@antrim.ifsgroup.com')->queue(new GenericError('CarrierAPi Error Data', $e->getMessage().' on line '.$e->getLine()."\r\n\r\n".json_encode($env_mode)));
            $this->mode = 'production';
        }
    }

    /**
     *
     */
    private function setCompanyServices()
    {
        $this->companyServices = new CompanyServices();
        $this->companyServices->setConsignment($this->consignment);
    }

    /**
     * Creates Shipment with Carrier and updates tables.
     *
     * @param  string  $mode  Used by APIController to overide mode
     *
     * @return response
     */
    public function createShipment($data, $mode = '')
    {
        $response = [];
        $this->setEnvironment($mode);
        $this->consignment = new Consignment($data);                            // Build Consignment and default missing items
        $apiShipment = new APIShipment();                                       // Shipment object with validation rules etc.
        $errors = $apiShipment->validateShipment($this->consignment->data);

        return (empty($errors)) ? $this->sendShipment()
            : $this->generateErrors($response, $errors);
    }

    /**
     * @return DHL\type|PrimaryFreight\type|TNT\type|mixed
     */
    private function sendShipment()
    {
        // Send shipment data to Carrier
        $this->carrier = Carrier::getInstanceOf($this->consignment->data['carrier_code'], $this->mode);
        $response = $this->carrier->createShipment($this->consignment->data);
        if (empty($response['errors'])) {
            $charges = Pricing::price($this->consignment->data);
            $response = $this->setResponsePricingFields($response, $charges);

            // Write shipment, charges and carrier Response to Database
            $shipment = $this->writeShipment($charges, $response);
            $shipmentCreated = (isset($shipment) && $shipment) ? true : false;

            // Add Carrier Consignment details to IFS response
            $response = $this->completeResponse($response, $shipmentCreated);
        }

        return $response;
    }

    /**
     * @param $response
     * @param $charges
     *
     * @return mixed
     */
    private function setResponsePricingFields($response, $charges)
    {
        $response['pricing'] = [];
        if (! $this->consignment->isCollect() && empty($charges['errors'])) {
            $response['pricing']['charges'] = $charges['sales'];
            $response['pricing']['vat_code'] = $charges['sales_vat_code'];
            $response['pricing']['vat_amount'] = $charges['sales_vat_amount'];
            $response['pricing']['total_cost'] = $charges['shipping_charge'] + $charges['sales_vat_amount'];
        }

        return $response;
    }

    /**
     * @param $charges
     * @param $response
     *
     * @return false|string
     */
    private function writeShipment($charges, $response)
    {
        $this->consignment->setPricingFields($charges);
        $this->consignment->addCarrierResponse($response);                      // Add package barcodes and tracking details etc
        $this->consignment->setShipmentToken();                                 // Get unique random token to identify Shipment

        return $this->addShipment();
    }

    /**
     * Update Shipment tables with Shipment data.
     *
     * @return string IFS Consignment number
     */
    private function addShipment()
    {
        // Any preprocessing necessary before saving shipment
        $this->consignment->preProcessAddShipment();


        try {
            if (isset($this->consignment->data['shipment_id']) && is_numeric($this->consignment->data['shipment_id'])) {
                // Shipment exists (saved) so update it
                $shipment = Shipment::find($this->consignment->data['shipment_id']);
                $this->consignment->data['consignment_number'] = $shipment->consignment_number; // hack
                $shipment->update($this->consignment->data);
            } else {
                // Shipment does not exist so create it
                $shipment = Shipment::create($this->consignment->data);
            }

            // Set status
            $shipment->setStatus('pre_transit', $this->consignment->data['user_id'], false, true, 'shipper');

            /*
             * *****************************************
             * Save Shipment content (commodity details)
             * *****************************************
             */
            if (isset($this->consignment->data['contents']) && ! empty($this->consignment->data['contents'])) {
                foreach ($this->consignment->data['contents'] as $content) {
                    $shipment->contents()->create($content);
                }
            }

            /*
             * *****************************************
             * Save Shipment content (package details)
             * *****************************************
             */
            if (isset($this->consignment->data['packages']) && ! empty($this->consignment->data['packages'])) {
                foreach ($this->consignment->data['packages'] as $package) {
                    $shipment->packages()->create($package);
                }
            }

            /*
             * *****************************************
             * Save PDF document (original base64 from carrier - 6x4)
             * *****************************************
             */
            foreach ($this->consignment->data['label_base64'] as $label) {
                $shipment->label()->create([
                    'base64' => $label['base64'],
                    'shipment_id' => $shipment->id,
                ]);
            }

            /*
             * *****************************************
             * Save Shipment alerts
             * *****************************************
             */

            /*
             * If we have a valid sender address and sender alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($this->consignment->data['alerts']['sender']) && filter_var($this->consignment->data['sender_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $this->consignment->data['sender_email'], 'type' => 's'] + $this->consignment->data['alerts']['sender'];
                $shipment->alerts()->create($alert);
            }

            /*
             * If we have a valid recipient address and recipient alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($this->consignment->data['alerts']['recipient']) && filter_var($this->consignment->data['recipient_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $this->consignment->data['recipient_email'], 'type' => 'r'] + $this->consignment->data['alerts']['recipient'];
                $shipment->alerts()->create($alert);
            }

            /*
             * If we have a valid broker address and broker alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($this->consignment->data['alerts']['broker']) && isset($this->consignment->data['broker_email']) && filter_var($this->consignment->data['broker_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $this->consignment->data['broker_email'], 'type' => 'b'] + $this->consignment->data['alerts']['broker'];
                $shipment->alerts()->create($alert);
            }

            /*
             * If we have a valid other address and other alerts have been requested,
             * create a record in the alerts table.
             */
            if (isset($this->consignment->data['alerts']['other']) && filter_var($this->consignment->data['other_email'], FILTER_VALIDATE_EMAIL)) {
                $alert = ['email' => $this->consignment->data['other_email'], 'type' => 'o'] + $this->consignment->data['alerts']['other'];
                $shipment->alerts()->create($alert);
            }

            /*
             * Create an alert request for the department associated with the shipment (problems only)
             */
            $shipment->setDepartmentAlerts();

            /*
             * Notify IFS staff (if parameters met)
             */
            $shipment->sendIfsNotifications();

            /*
             * Create a collection request for the transport department
             */
            $shipment->createCollectionRequest();
        } catch (Exception $e) {
            Mail::to('it@antrim.ifsgroup.com')->queue(new GenericError('WebClient DB Error', $e->getMessage().' on line '.$e->getLine()."\r\n\r\n".json_encode($this->consignment->data)));

            // Return false to signify problem
            return false;
        }

        return $shipment;
    }

    /**
     * @param $response
     * @param $shipmentCreated
     *
     * @return mixed
     */
    private function completeResponse($response, $shipmentCreated)
    {
        if (strtolower($this->mode) == 'test' || $shipmentCreated) {
            // Everything good so return token, consignment number and tracking URL for shipment
            $response['ifs_consignment_number'] = $this->consignment->data['consignment_number'];
            $response['token'] = $this->consignment->data['token'];
            $response['tracking_url'] = config('app.url').'/tracking/'.$this->consignment->data['token'];
        } else {
            // Problem saving details - so return and error
            $response['errors'][] = 'System Error (IT Support Notified)';
            $response['label_base64'][0]['base64'] = '';
        }

        return $response;
    }

    /**
     * @param $response
     * @param $errors
     *
     * @return mixed
     */
    private function generateErrors($response, $errors)
    {
        if (is_array($errors)) {
            foreach ($errors as $error) {
                $response['errors'][] = $error;
            }
        } else {
            $response['errors'][] = $errors;
        }

        return $response;
    }

    /**
     * Delete Shipment function.
     *
     * @param  type  $this  ->consignment->data
     * @param  string  $mode  Used by APIController to overide mode
     *
     * @return string
     */
    public function deleteShipment($mode = '')
    {
        $response = [];
        $this->setEnvironment($mode);

        // Identify Shipment
        $shipment = Shipment::where('company_id', $this->consignment->data['company_id'])
            ->where('token', $this->consignment->data['shipment_token'])
            ->first();
        if ($shipment) {
            if ($shipment->isCancellable()) {
                $this->carrier = Carrier::getInstanceOf($shipment->carrier->code, $this->mode);     // Create Carrier Object
                $response = $this->carrier->deleteShipment($shipment);                              // Send Shipment to Carrier

                if ($response['errors'] == []) {
                    $shipment->setCancelled($this->consignment->data['user_id']);
                }
            } else {
                $response['errors'][] = 'Shipment cannot be cancelled';
            }
        } else {
            $response['errors'][] = 'Shipment not found';
        }

        return $response;
    }

    /**
     * Generates a commercial invoice.
     *
     * @param  string  $token  Shipment identifier.
     * @param  array  $parameters  An array of options for customising invoice.
     * @param  string  $size  Size of the PDF document required (accepts codes defined in print formats table).
     * @param  string  $output  Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    public function getCommercialInvoice($token, $parameters = [], $size = 'A4', $output = 'S')
    {
        $pdf = new Pdf($size, $output);

        return $pdf->createCommercialInvoice($token, $parameters);
    }

    /**
     * Create a despatch note.
     *
     * @param  type  $token
     * @param  type  $size
     * @param  type  $output
     *
     * @return type
     */
    public function getDespatchNote($token, $size = 'A4', $output = 'S')
    {
        $pdf = new Pdf($size, $output);

        return $pdf->createDespatchNote($token);
    }

    /**
     * Get a batch of labels.
     *
     * @param  mixed  $shipment_id  Loaded shipment model or shipment identifier.
     * @param  string  $size  Size of the PDF document required (accepts codes defined in print formats table).
     * @param  string  $output  Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    private function getLabels($shipments, $size = 'A4', $output = 'S', $labelType = '')
    {
        if ($shipments) {
            $doc = new TCPDI();
            $doc->setPrintHeader(false);
            $doc->setPrintFooter(false);
            $hasContent = false;
            foreach ($shipments as $shipment) {
                // Get PDF string for this shipment
                $originalPdf = $this->getLabel($shipment, $size, 'S', false, $labelType);

                if ($originalPdf != 'not found') {
                    $hasContent = true;
                    $pageCount = $doc->setSourceData($originalPdf);

                    // Import Page by Page
                    for ($page = 0; $page < $pageCount; $page++) {
                        // Import PDF page to working area as Image and get size
                        $tpl = $doc->importPage($page + 1);
                        $originalPdfSize = $doc->getTemplateSize($tpl);

                        // Add a blank page to the document, then add content as a Template
                        $doc->AddPage('P', [$originalPdfSize['w'], $originalPdfSize['h']]);
                        $doc->useTemplate($tpl);
                    }
                }
            }

            if ($hasContent) {
                return $doc->Output(date('Ymdhis').'.pdf', $output);
            } else {
                abort(404);
            }
        }

        return null;
    }

    /**
     * Takes an unaltered PDF from a carrier and returns it in the size requested
     * with the addition of printing/folding instructions for A4/LETTER sizes.
     *
     * @param  mixed  $shipment  Loaded shipment model or shipment identifier.
     * @param  string  $size  Size of the PDF document required (accepts codes defined in print formats table).
     * @param  string  $output  Valid values are (D) - download, (S) - base64 string, (I) - inline browser. *** All external API calls should use (S). Therefor param 3 should not be publicly available ***
     *
     * @return  mixed
     */
    private function getLabel($shipment, $size = 'A4', $output = 'S', $encoded = true, $labelType = '')
    {
        $pdf = new Pdf($size, $output);

        return $pdf->createLabel($shipment, $encoded, $labelType);
    }
}
