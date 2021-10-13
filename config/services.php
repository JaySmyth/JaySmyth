<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'fxrs' => [
        'url' => env('FEDEX_HOST'),
        'port' => '2000',
    ],

    'rfserver' => [
        'name' => env('RFSERVER'),
        'path_to_node' => env('PATH_TO_NODE'),
        'host' => env('APP_HOST'),
    ],

    'easypost' => [
        'key' => env('EASYPOST_KEY'),
    ],

    'cartrover' => [
        'api_user' => env('CARTROVER_API_USER'),
        'api_key' => env('CARTROVER_API_KEY'),
    ],

    'ups' => [
        'user' => env('UPS_API_USER'),
        'password' => env('UPS_API_PASSWORD'),
        'key' => env('UPS_API_KEY'),
        'url' => env('UPS_API_URL'),
    ],

    'tnt' => [
        'user' => env('TNT_API_USER'),
        'password' => env('TNT_API_PASSWORD'),
    ],

    'dbschenker' => [
        'user' => env('DBSCHENKER_API_USER'),
        'password' => env('DBSCHENKER_API_PASSWORD'),
    ],

    'dx' => [
        'user' => env('DX_API_USER'),
        'password' => env('DX_API_PASSWORD'),
        'url' => env('DX_API_URL'),
    ],

    'express' => [
        'url' => env('EXPRESS_API_URL'),
        'id' => env('EXPRESS_API_ID'),
        'secret' => env('EXPRESS_API_SECRET'),
        'username' => env('EXPRESS_API_USERNAME'),
        'password' => env('EXPRESS_API_PASSWORD'),
    ]

];
