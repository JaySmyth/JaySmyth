<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\CarrierAPI;

use Illuminate\Support\Facades\Response;

/**
 * Description of Respond.
 *
 * @author gmcbroom
 */
class APIResponse
{
    public $statusCode = '200';
    public $transactionHeader = 'IFS API Transaction';
    public $version = '2';

    private function getVersion()
    {
        return $this->version;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function respondUnAuthorized($message = 'UnAuthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    public function respondNotAvailable($message = 'Mode not Available')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    public function respondInvalid($message = 'Validation Errors')
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }

    public function respondNoCarrierAvailable($message = 'No Carrier available for service')
    {
        return $this->setStatusCode(400)->respondWithError($message);
    }

    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respondNotSupported($message = 'Method/ URI not supported')
    {
        return $this->setStatusCode(405)->respondWithError($message);
    }

    public function getValue(&$item, $default = '')
    {
        return (! empty($item)) ? $item : $default;
    }

    public function getHeaders()
    {
        return [
            'transaction_header' => $this->transactionHeader,
            'version' => $this->getVersion(),
            'status' => $this->getStatusCode(),
        ];
    }

    public function respondCreatedShipment($reply, $version)
    {
        $this->version = $version;
        if ($reply['errors'] == []) {
            return $this->setStatusCode(201)->respond([
                        'meta' => $this->getHeaders(),
                        'data' => $reply,
            ]);
        } else {
            return $this->setStatusCode(400)->respondWithError($reply);
        }
    }

    public function respondDeletedShipment($reply = '', $version = '')
    {
        $this->version = $version;
        if ($reply['errors'] == []) {
            return $this->setStatusCode(200)->respond([
                        'meta' => $this->getHeaders(),
                        'data' => $reply,
            ]);
        } else {
            return $this->setStatusCode(400)->respondWithError($reply);
        }
    }

    public function respondPricedShipment($reply = [], $version = '')
    {
        $this->version = $version;
        if (isset($reply['errors']) && $reply['errors'] == []) {
            $response['errors'] = [];
            $response['pricing']['charges'] = $reply['sales'];
            $response['pricing']['vat_code'] = $reply['sales_vat_code'];
            $response['pricing']['vat_amount'] = $reply['sales_vat_amount'];
            $response['pricing']['total_cost'] = $reply['shipping_charge'];

            $this->setStatusCode(201);

            return $this->respond([
                        'meta' => $this->getHeaders(),
                        'data' => $response,
            ]);
        } else {
            return $this->setStatusCode(400)->respondWithError($reply);
        }
    }

    public function respondWithError($errors)
    {
        $i = 0;

        if (isset($errors['errors']) && is_array($errors['errors'])) {
            foreach ($errors['errors'] as $value) {
                $errorResponse[$i]['message'] = $value;
                $i++;
            }
        } else {
            $errorResponse[$i]['message'] = $errors;
        }

        return $this->respond([
                    'meta' => $this->getHeaders(),
                    'errors' => $errorResponse,
        ]);
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
