<?php

namespace App\DbSchenker;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DbSchenker
{
    protected $url;
    protected $user;
    protected $password;

    public function __construct(bool $testMode = false)
    {
        $this->url = config('services.dbschenker.url');
        $this->user = config('services.dbschenker.user');
        $this->password = config('services.dbschenker.password');
    }

    public function sendRequest(array $request)
    {
        try {
            // New guzzle client
            $client = new Client();

            // Send the request and get the response
            $response = $client->post($this->url.'ReceiveShipment', ['auth' => [$this->user, $this->password], 'json' => $request]);

            // Get the response body
            return $response->getBody()->getContents();

        } catch (GuzzleException $exception) {
            dd($exception);
        }
    }
}
