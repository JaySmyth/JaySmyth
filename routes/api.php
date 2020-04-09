<?php

use App\Models\CarrierAPI\Facades\APIResponse;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::get('label/{id}', 'APIController@printLabel');
Route::post('{version}/shipments/price', 'APIController@priceShipment');
Route::post('{version}/shipments', 'APIController@createShipment');
Route::delete('{version}/shipments/{ifs_consignment_number}/{company_code}', 'APIController@deleteShipment');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('{version}/users/validate', 'APIController@validateUser');
    Route::post('{version}/shipments/cancel', 'APIController@cancelShipment');
    Route::get('{version}/label/png', 'APIController@labelPng');
});

/*
  |--------------------------------------------------------------------------
  | Easypost
  |--------------------------------------------------------------------------
 */

Route::post('easypost-webhook', 'TrackingController@easypostWebhook');

/*
  |--------------------------------------------------------------------------
  | Shipments (called from Vendorvillage)
  |--------------------------------------------------------------------------
 */

Route::post('save-shipment', 'ShipmentsController@saveShipment');

/*
  |--------------------------------------------------------------------------
  | RF Scanner - called by nodeJs
  |--------------------------------------------------------------------------
 */
Route::post('rf', 'RfController@server');

/*
  |--------------------------------------------
  | Catch any invalid routes
  |--------------------------------------------
 */
Route::get('{all}', 'ApiController@notSupported');
Route::post('{all}', 'ApiController@notSupported');
Route::delete('{all}', 'ApiController@notSupported');
