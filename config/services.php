<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Third Party Services
      |--------------------------------------------------------------------------
      |
      | This file is for storing the credentials for third party services such
      | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
      | default location for this type of information, allowing packages
      | to have a conventional place to find your various credentials.
      |
     */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],
    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],
    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION'),
    ],
    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'redis' => [
        'host' => env('REDIS_HOST'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT'),
    ],
    'fxrs' => [
        'url' => env('FEDEX_HOST'),
        'port' => '2000'
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
];