<?php

namespace app\CarrierAPI;

use App\Carrier;
use Illuminate\Support\Facades\Validator;

/**
 * Description of DHLWebAPI.
 *
 * @author gmcbroom
 */
class CarrierBase
{

    protected $siteId;
    protected $password;
    protected $accounts;
    protected $mode;
    protected $connection;
    protected $labelStockType;
    protected $packageTypes;
    protected $weightUnits;
    protected $dimensionUnits;
    protected $logo;
    protected $transactionHeader = 'Test Transaction';
    protected $time_start;
    protected $time_finish;
    protected $environment;

    public function __construct($mode)
    {
        /*
         * *****************************************
         * Define fields for Production/ Development
         * *****************************************
         */

        // Set operating mode - test/ production
        $this->mode = $mode;

        // TransactionLog::create(['carrier' => 'Unknown', 'type' => 'DEBUG', 'direction' => '-', 'msg' => 'CarrierBase fn construct', 'mode' => $this->mode]);
    }

    public function setVersion()
    {
        return 'IFS_API_1.1';
    }

    public function logMsg($data = '', $msg = '', $type = '')
    {
        //log a transaction to the database
        if (isset($data['ShipCompanyName'])) {
            $sender = $data['ShipCompanyName'];
        } elseif (isset($data['ShipContact'])) {
            $sender = $data['ShipContact'];
        } else {
            $sender = '';
        }

        if (isset($data['CneeCompanyName'])) {
            $destination = $data['CneeCompanyName'];
        } elseif (isset($data['CneeContact'])) {
            $destination = $data['CneeContact'];
        } else {
            $destination = '';
        }

        $fx_log = new ApiLog();
        $fx_log->SetField('type', $type);
        $fx_log->SetField('msg', escape_data($msg));
        $fx_log->SetField('sender', $sender);
        $fx_log->SetField('destination', $destination);
        $fx_log->SetField('date', date('d-m-y'));
        $fx_log->SetField('time', date('H:i:s'));
        $fx_log->Insert();
    }

    /**
     * Function to allow timing of events with milliseconds
     * Use by calling $this->timer('start'); to start the timer
     * and
     * echo "Time taken : " . $this->timer('finish') . " Secs<br>";
     * to display
     * 
     * @param type $mode
     * @return decimal seconds
     */
    private function timer($mode)
    {

        $diff = '';
        switch ($mode) {
            case 'start':
                $this->time_start = microtime(true);
                break;

            case 'finish':
                $this->time_finish = microtime(true);
                $diff = round($this->time_finish - $this->time_start, 3);
                $this->time_start = $this->time_finish;
                break;

            default:
                break;
        }

        return $diff;
    }

    public function deleteShipment($shipment)
    {

        $response['errors'] = [];
        $response['carrier_code'] = Carrier::find($shipment['carrier_id'])->code;
        $response['consignment_number'][0] = $shipment['consignment_number'];

        return $response;
    }

    public function addServiceRules($shipment, $rules = '')
    {

        // Check Carrier/ Service combination is valid
        $carrier = Carrier::where('code', $shipment['carrier_code'])->first();
        if (!$carrier->getServices($shipment['service_code'])) {
            $rules['service_code'] = 'not_in:' . $shipment['service_code'];
        }

        return $rules;
    }

    public function validateShipment($shipment)
    {
        $errors = [];

        $shipmentValidation = Validator::make($shipment, [
        ]);

        if ($shipmentValidation->fails()) {
            $errors = $this->buildValidationErrors($shipmentValidation->errors());
        }

        return $errors;
    }

    public function buildValidationErrors($messages)
    {
        foreach ($messages->all() as $message) {
            $errors[] = $message;
        }

        return $errors;
    }

    public function generateErrorResponse($response, $errors)
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

    public function getElement($shipment, $target)
    {
        $locArr = explode('.', $target);
        $data = '';

        switch (count($locArr)) {

            case '1':
                if (isset($shipment[$locArr[0]])) {
                    $data = $shipment[$locArr[0]];
                }
                break;

            case '2':
                if (isset($shipment[$locArr[0]][$locArr[1]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]];
                }
                break;

            case '3':
                if (isset($shipment[$locArr[0]][$locArr[1]][$locArr[2]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]][$locArr[2]];
                }
                break;

            case '4':
                if (isset($shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]];
                }
                break;

            case '5':
                if (isset($shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]][$locArr[4]])) {
                    $data = $shipment[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]][$locArr[4]];
                }
                break;
        }

        return $data;
    }

    public function setElement($data, $target, $value)
    {

        $locArr = explode('.', $target);
        switch (count($locArr)) {
            case '1':
                $data[$locArr[0]] = $value;
                break;

            case '2':
                $data[$locArr[0]][$locArr[1]] = $value;
                break;

            case '3':
                $data[$locArr[0]][$locArr[1]][$locArr[2]] = $value;
                break;

            case '4':
                $data[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]] = $value;
                break;

            case '5':
                $data[$locArr[0]][$locArr[1]][$locArr[2]][$locArr[3]][$locArr[4]] = $value;
                break;
        }

        return $data;
    }

    public function getData($data, $key)
    {

        // Check to see if data contains a multivalue item
        $pos = strpos($key, '.*.');
        if ($pos !== false) {

            // Pull back Array
            $reply = $this->getElement($data, substr($key, 0, $pos));

            if (is_array($reply)) {
                foreach ($reply as $item) {

                    // Get only the Key value I am looking for
                    $result[] = $item[substr($key, $pos + 3)];
                }
            } else {
                $result[] = '';
            }
        } else {

            // Process as standard
            $result = $this->getElement($data, $key);
        }

        return $result;
    }

    public function generateSuccess()
    {
        $response['errors'] = [];

        return $response;
    }

    /**
     * Validate shipment details using supplied rules
     * @param array $rules
     * @param array $shipment
     * 
     * @return array $errors
     */
    public function applyRules($rules, $shipment, $messages = array())
    {
        $errors = array();
        if ($rules != '') {
            $shipmentValidation = Validator::make($shipment, $rules, $messages);

            if ($shipmentValidation->fails()) {
                // Returns Errors as an array
                $errors = $this->buildValidationErrors($shipmentValidation->errors());
            }
        }

        return $errors;
    }

}
