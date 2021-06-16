<?php

// Temporary route for testing
Route::get('test/{id}', 'APIController@test');
Route::get('reprice/{shipment}', 'APIController@reprice');

// Send feedback
Route::post('feedback-modal', 'FeedbackController');

// Wrap routes in a Session timeout wrapper (May need to move Auth routes outside)
//Route::group(['middleware' => 'timeout'], function () {

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */

Route::get('addresses/autocomplete', 'AddressesController@autocomplete');
Route::post('services/available', 'ServicesController@available');
Route::get('services', 'ServicesController@index');
Route::get('service/message/{id}', 'ServicesController@serviceMessage');

Route::get('preferences', 'PreferencesController@getPreferences');
Route::put('preferences', 'PreferencesController@setPreferences');
Route::delete('preferences', 'PreferencesController@resetPreferences');

/*
  |--------------------------------------------------------------------------
  | Shipment Routes
  |--------------------------------------------------------------------------
 */
Route::get('/', 'ShipmentsController@create');
Route::get('shipments/{shipments}/cancel', 'ShipmentsController@cancel');
Route::get('shipments/{shipments}/hold', 'ShipmentsController@hold');
Route::get('shipments/{shipments}/receive', 'ShipmentsController@receive');
Route::get('shipments/{shipments}/raw-data', 'ShipmentsController@rawData');
Route::get('shipments/{shipments}/send-test-email', 'ShipmentsController@sendTestEmail');
Route::get('shipments/{shipments}/transaction-log', 'ShipmentsController@transactionLog');
Route::get('shipments/{shipments}/remove-pod', 'ShipmentsController@removePod');
Route::get('shipments/{shipments}/undo-cancel', 'ShipmentsController@undoCancel');
Route::get('shipments/{shipments}/form-view', 'ShipmentsController@formView');
Route::get('shipments/{shipments}/price', 'ShipmentsController@price');
Route::get('shipments/{shipments}/test', 'ShipmentsController@test');
Route::get('shipments/{shipment}/reset', 'ShipmentsController@reset');
Route::post('shipments/{shipment}/reset', 'ShipmentsController@resetShipment');

Route::put('shipments/save', 'ShipmentsController@save');
Route::get('shipments/get', 'ShipmentsController@getSaved');

Route::get('shipments/download', 'ShipmentsController@download');
Route::get('shipments/download-dims', 'ShipmentsController@downloadDims');
Route::get('shipments/download-exceptions', 'ShipmentsController@downloadExceptions');
Route::get('shipments/upload', 'ShipmentsController@upload');
Route::post('shipments/upload', 'ShipmentsController@storeUpload');
Route::get('shipments/status-upload', 'ShipmentsController@statusUpload');
Route::post('shipments/status-upload', 'ShipmentsController@storeStatusUpload');
Route::get('label/{token}', 'ShipmentsController@label');
Route::get('labels/{token}/{userid}', 'ShipmentsController@labels');

Route::get('shipments/batched-commercial-invoices', 'ShipmentsController@batchedCommercialInvoicesPdf');
Route::get('commercial-invoice/{token}', 'ShipmentsController@commercialInvoice');
Route::get('commercial-invoices/{source}', 'ShipmentsController@batchedCommercialInvoicesBySourcePdf');
Route::get('despatch-note/{token}', 'ShipmentsController@despatchNote');
Route::get('despatch-notes/{source}', 'ShipmentsController@batchedDespatchNotesBySourcePdf');

Route::get('shipments/collection-manifest', 'ShipmentsController@collectionManifestPdf');
Route::get('shipments/batched-labels/{labelType}', 'ShipmentsController@batchedMasterLabelsPdf');
Route::get('shipments/batched-shipping-docs/{labelType}', 'ShipmentsController@batchedShippingDocsPdf');

Route::get('shipments/pod', 'ShipmentsController@pod');
Route::post('shipments/pod', 'ShipmentsController@updatePod');

Route::post('shipments/{shipments}/update-dims', 'ShipmentsController@updateDims');
Route::get('shipments/update-dims', 'ShipmentsController@dims');
Route::get('shipments/rts', 'ShipmentsController@closeOutReturnedShipments');
Route::get('shipments/todays-labels', 'ShipmentsController@todaysLabels');

Route::resource('shipments', 'ShipmentsController');

/*
  |--------------------------------------------------------------------------
  | Invoice Runs Routes
  |--------------------------------------------------------------------------
 */

Route::get('invoice-runs', 'InvoiceRunController@index');
Route::get('invoice-runs/create', 'InvoiceRunController@create');
Route::get('invoice-runs/{id}', 'InvoiceRunController@show');
Route::post('invoice-runs', 'InvoiceRunController@store');

/*
  |--------------------------------------------------------------------------
  | Account Routes
  |--------------------------------------------------------------------------
 */

Route::get('account', 'AccountController@show');
Route::get('account/settings', 'AccountController@edit');
Route::patch('account/settings', 'AccountController@update');
Route::get('account/password', 'AccountController@password');
Route::post('account/password', 'AccountController@changePassword');

/*
  |--------------------------------------------------------------------------
  | User Routes
  |--------------------------------------------------------------------------
 */
Route::get('users/{users}/add-company', 'UsersController@addCompany');
Route::post('users/{users}/add-company', 'UsersController@storeCompany');
Route::get('users/{users}/reset-password', 'UsersController@resetPassword');
Route::post('users/{users}/reset-password', 'UsersController@updatePassword');
Route::get('users/{users}/remove-company/{companyId}', 'UsersController@removeCompany');

Route::resource('users', 'UsersController');

/*
  |--------------------------------------------------------------------------
  | Tracking Routes
  |--------------------------------------------------------------------------
 */

Route::post('tracking', 'TrackingController@store');
Route::get('tracking/{shipmentId}/create', 'TrackingController@create');
Route::put('tracking/{tracking}', 'TrackingController@update');
Route::delete('tracking/{tracking}', 'TrackingController@destroy');
Route::get('tracking/{token}/{type?}', 'TrackingController@show');
Route::get('tracking/{tracking}/edit', 'TrackingController@edit');
Route::get('track', 'TrackingController@track');
Route::post('track', 'TrackingController@trackShipment');

Route::get('tracker/{consignment}', 'TrackingController@tracker');

Route::get('create-tracker', 'TrackingController@createEasypostTracker');
Route::post('create-tracker', 'TrackingController@sendTrackerRequest');
Route::get('bulk-create-trackers', 'TrackingController@bulkCreateTrackers');

Route::get('easypost-push', 'TrackingController@requestPushToWebhook');

/*
  |--------------------------------------------------------------------------
  | Document Routes (supporting documentation)
  |--------------------------------------------------------------------------
 */

Route::get('documents/create/{parent}/{id}', 'DocumentsController@create');
Route::post('documents', 'DocumentsController@store');

Route::delete('documents/{documents}', 'DocumentsController@destroy');

/*
  |--------------------------------------------------------------------------
  | Company Routes
  |--------------------------------------------------------------------------
 */
Route::get('companies/{companies}/services', 'CompaniesController@services');
Route::post('companies/{companies}/services', 'CompaniesController@setServices');
Route::get('companies/{companies}/status', 'CompaniesController@status');
Route::post('companies/{companies}/status', 'CompaniesController@updateStatus');
Route::get('companies/download', 'CompaniesController@download');
Route::get('companies/{companies}/collection-settings', 'CompaniesController@collectionSettings');
Route::post('companies/{companies}/collection-settings', 'CompaniesController@storeCollectionSettings');

Route::resource('companies', 'CompaniesController');
Route::get('localisation', 'CompaniesController@getLocalisation');

/*
  |--------------------------------------------------------------------------
  | Company Service Rate Routes
  |--------------------------------------------------------------------------
 */
Route::get('company-service-rate/{companies}/{services}/delete', 'CompaniesController@deleteCompanyRates');
Route::get('company-service-rate/{companies}/{services}', 'CompaniesController@viewCompanyRates');
Route::post('company-service-rate/{companies}/{services}', 'CompaniesController@setCompanyRates');
/*
  |--------------------------------------------------------------------------
  | Commodity Routes
  |--------------------------------------------------------------------------
 */

Route::resource('commodities', 'CommoditiesController');

/*
  |--------------------------------------------------------------------------
  | Addresses Routes (senders/recipients)
  |--------------------------------------------------------------------------
 */
Route::get('addresses/import-recipient-addresses', 'AddressesController@import');
Route::post('import-recipients', 'AddressesController@storeImport');
Route::resource('addresses', 'AddressesController');

/*
  |--------------------------------------------------------------------------
  | Transport Addresses
  |--------------------------------------------------------------------------
 */
Route::resource('transport-addresses', 'TransportAddressesController');

/*
  |--------------------------------------------------------------------------
  | Purchase Invoice Routes
  |--------------------------------------------------------------------------
 */

Route::get('purchase-invoices/{id}/detail', 'PurchaseInvoicesController@detail');
Route::get('purchase-invoices/{id}/compare', 'PurchaseInvoicesController@compare');
Route::get('purchase-invoices/{id}/pass', 'PurchaseInvoicesController@pass');
Route::get('purchase-invoices/{id}/export', 'PurchaseInvoicesController@export');
Route::get('purchase-invoices/{id}/receive', 'PurchaseInvoicesController@receive');
Route::get('purchase-invoices/{id}/query', 'PurchaseInvoicesController@query');
Route::get('purchase-invoices/{id}/costs', 'PurchaseInvoicesController@costs');
Route::get('purchase-invoices/{id}/copy-docs', 'PurchaseInvoicesController@copyDocs');
Route::get('purchase-invoices/{id}/cost-comparison-download', 'PurchaseInvoicesController@costComparisonDownload');
Route::get('purchase-invoices/{id}/negative-variances-email', 'PurchaseInvoicesController@negativeVariancesEmail');
Route::get('purchase-invoices/{id}/negative-variances-download', 'PurchaseInvoicesController@negativeVariancesDownload');
Route::get('purchase-invoices/{id}/preview-xml', 'PurchaseInvoicesController@previewXml');
Route::get('purchase-invoices/{id}/download-xml', 'PurchaseInvoicesController@downloadXml');

Route::get('purchase-invoices/download', 'PurchaseInvoicesController@download');
Route::get('purchase-invoices/copy-docs-email', 'PurchaseInvoicesController@copyDocsEmail');
Route::get('purchase-invoices/export-invoices', 'PurchaseInvoicesController@exportInvoices');

Route::resource('purchase-invoices', 'PurchaseInvoicesController');

/*
 *
 */

Route::view('dim-check', 'dim_check/upload');
Route::post('dim-check', 'DimCheckController@processUpload');

/*
  |--------------------------------------------------------------------------
  | Manifest Routes
  |--------------------------------------------------------------------------
 */
Route::get('manifests/{manifests}/add-shipment', 'ManifestsController@addShipment');
Route::get('manifests/{manifests}/summary', 'ManifestsController@summary');
Route::post('manifests/add-shipment/{id}', 'ManifestsController@storeShipment');
Route::get('manifests/{manifests}/download', 'ManifestsController@download');
Route::get('manifests/{manifests}/pdf', 'ManifestsController@pdf');
Route::resource('manifests', 'ManifestsController');

/*
  |--------------------------------------------------------------------------
  | Manifest Profile Routes
  |--------------------------------------------------------------------------
 */

Route::get('manifest-profiles/run', 'ManifestProfilesController@run');
Route::post('manifest-profiles/run/{id}', 'ManifestProfilesController@runManifest');
Route::post('bulk-hold', 'ManifestProfilesController@bulkHold');
Route::resource('manifest-profiles', 'ManifestProfilesController');

/*
  |--------------------------------------------------------------------------
  | Mail Report Routes
  |--------------------------------------------------------------------------
 */

Route::get('mail-reports/{id}/add-recipient', 'MailReportsController@addRecipient');
Route::post('mail-reports/{id}/add-recipient', 'MailReportsController@storeRecipient');
Route::get('mail-reports/{id}/edit-recipient/{recipient}', 'MailReportsController@editRecipient');
Route::patch('mail-reports/{id}/edit-recipient/{recipient}', 'MailReportsController@updateRecipient');
Route::resource('mail-reports', 'MailReportsController');

/*
  |--------------------------------------------------------------------------
  | File Uploads Routes
  |--------------------------------------------------------------------------
 */
Route::get('file-uploads/{id}/retry', 'FileUploadsController@retry');
Route::resource('file-uploads', 'FileUploadsController');

/*
  |--------------------------------------------------------------------------
  | Reports Routes
  |--------------------------------------------------------------------------
 */

Route::get('reports', 'ReportsController@index');
Route::get('reports/fedex-customs/{id}', 'ReportsController@fedexCustoms');
Route::get('reports/shippers/{id}', 'ReportsController@shippers');
Route::get('reports/daily-stats/{id}', 'ReportsController@dailyStats');
Route::get('reports/non-shippers/{id}', 'ReportsController@nonShippers');
Route::get('reports/scanning/{id}', 'ReportsController@scanning');
Route::get('reports/dims/{id}', 'ReportsController@dims');
Route::get('reports/active-shipments/{id}', 'ReportsController@activeShipments');
Route::get('reports/exceptions/{id}', 'ReportsController@exceptions');
Route::get('reports/pod/{id}', 'ReportsController@pod');
Route::get('reports/purchase-invoices/unknown-jobs/{id}', 'ReportsController@unknownJobs');
Route::get('reports/user-agents/{id}', 'ReportsController@userAgents');
Route::get('reports/fedex-international-available/{id}', 'ReportsController@fedexInternationalAvailable');
Route::get('reports/margins/{id}', 'ReportsController@margins');
Route::get('reports/carrier-scans/{id}', 'ReportsController@carrierScans');
Route::get('reports/purchase-invoice-lines/{id}', 'ReportsController@purchaseInvoiceLines');
Route::get('reports/pre-transit/{id}', 'ReportsController@preTransit');
Route::get('reports/haz-dry-ice/{id}', 'ReportsController@hazardous');
Route::get('reports/collection-settings/{id}', 'ReportsController@collectionSettings');
Route::get('reports/shipments-by-carrier/{id}', 'ReportsController@shipmentsByCarrier');
Route::get('reports/scanning-kpis/{id}', 'ReportsController@scanningKpis');
Route::get('reports/label-downloads/{id}', 'ReportsController@labelDownloads');
Route::get('reports/performance/{id}', 'ReportsController@performance');

/*
  |--------------------------------------------------------------------------
  | Role Routes
  |--------------------------------------------------------------------------
 */
Route::get('roles', 'RolesController@index');
Route::get('getroles', 'RolesController@getRoles');

Route::get('roles/{roles}/permissions', 'RolesController@permissions');
Route::post('roles/permissions/{id}', 'RolesController@setPermissions');

Route::get('states', 'StatesController@getStates');

/*
  |--------------------------------------------------------------------------
  | Authentication Routes
  |--------------------------------------------------------------------------
  |
  | Authentication routes have been explicity defined instead of using
  | Auth::routes(); becuase we do not want to provide endpoints to user
  | registration pages.
  |
 */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

/*
  |--------------------------------------------------------------------------
  | Help Routes
  |--------------------------------------------------------------------------
 */
Route::get('help', 'HelpController@index');
Route::get('feedback', 'HelpController@feedback');
Route::post('feedback', 'HelpController@sendFeedback');
Route::get('covid', 'HelpController@covid');

/*
  |--------------------------------------------------------------------------
  | Customs Entries Routes
  |--------------------------------------------------------------------------
 */
Route::get('customs-entries/{customs_entry}/add-commodity', 'CustomsEntriesController@addCommodity');
Route::post('customs-entries/{customs_entry}/add-commodity', 'CustomsEntriesController@storeCommodity');
Route::get('customs-entries/download', 'CustomsEntriesController@download');
Route::get('customs-entries/download-commodity', 'CustomsEntriesController@downloadByCommodity');
Route::get('customs-entries/type/{company}', 'CustomsEntriesController@companyType');
Route::resource('customs-entries', 'CustomsEntriesController');

Route::get('customs-entry-commodity/{customs_entry_commodity}/edit', 'CustomsEntryCommoditiesController@edit');
Route::patch('customs-entry-commodity/{customs_entry_commodity}', 'CustomsEntryCommoditiesController@update');
Route::delete('customs-entry-commodity/{customs_entry_commodity}', 'CustomsEntryCommoditiesController@destroy');

/*
  |--------------------------------------------------------------------------
  | Quotations Routes
  |--------------------------------------------------------------------------
 */

Route::post('quotations/{quotation}/status', 'QuotationsController@status');
Route::get('quotations/{quotation}/pdf', 'QuotationsController@pdf');
Route::resource('quotations', 'QuotationsController');

/*
  |--------------------------------------------------------------------------
  | Sea Freight Routes
  |--------------------------------------------------------------------------
 */

Route::get('sea-freight/{id}/cancel', 'SeaFreightShipmentController@cancel');
Route::get('sea-freight/{id}/add-container', 'SeaFreightShipmentController@addContainer');
Route::post('sea-freight/{id}/add-container', 'SeaFreightShipmentController@storeContainer');
Route::get('sea-freight/{id}/edit-container/{container}', 'SeaFreightShipmentController@editContainer');
Route::patch('sea-freight/{id}/edit-container/{container}', 'SeaFreightShipmentController@updateContainer');
Route::get('sea-freight/{id}/edit-seal-number/{container}', 'SeaFreightShipmentController@editSeal');
Route::patch('sea-freight/{id}/edit-seal-number/{container}', 'SeaFreightShipmentController@updateSeal');
Route::get('sea-freight/{id}/process', 'SeaFreightShipmentController@process');
Route::post('sea-freight/{id}/process', 'SeaFreightShipmentController@storeProcess');
Route::get('sea-freight/{id}/status', 'SeaFreightShipmentController@status');
Route::post('sea-freight/{id}/status', 'SeaFreightShipmentController@updateStatus');
Route::get('sea-freight/download', 'SeaFreightShipmentController@download');
Route::resource('sea-freight', 'SeaFreightShipmentController');
Route::get('sea-freight-tracking/{seaFreightTracking}/edit', 'SeaFreightTrackingController@edit');
Route::patch('sea-freight-tracking/{seaFreightTracking}', 'SeaFreightTrackingController@update');

/*
  |--------------------------------------------------------------------------
  | Fuel Surcharges
  |--------------------------------------------------------------------------
 */

Route::get('fuel-surcharges/upload', 'FuelSurchargesController@upload');
Route::post('fuel-surcharges/upload', 'FuelSurchargesController@storeupload');
Route::resource('fuel-surcharges', 'FuelSurchargesController');

/*
  |--------------------------------------------------------------------------
  | Currencies
  |--------------------------------------------------------------------------
 */

Route::resource('currencies', 'CurrenciesController');

/*
  |--------------------------------------------------------------------------
  | Currencies
  |--------------------------------------------------------------------------
 */

Route::resource('carrier-charge-codes', 'CarrierChargeCodesController');
//});

/*
  |--------------------------------------------------------------------------
  | Drivers
  |--------------------------------------------------------------------------
 */
Route::resource('drivers', 'DriversController');

/*
  |--------------------------------------------------------------------------
  | Vehicles
  |--------------------------------------------------------------------------
 */
Route::resource('vehicles', 'VehiclesController');

/*
  |--------------------------------------------------------------------------
  | Driver Manifests
  |--------------------------------------------------------------------------
 */
Route::get('driver-manifests/{id}/close', 'DriverManifestsController@close');
Route::get('driver-manifests/{id}/open', 'DriverManifestsController@open');
Route::get('driver-manifests/{id}/pdf', 'DriverManifestsController@pdf');
Route::get('driver-manifests/{id}/dockets', 'DriverManifestsController@dockets');
Route::resource('driver-manifests', 'DriverManifestsController');

/*
  |--------------------------------------------------------------------------
  | Transport Jobs
  |--------------------------------------------------------------------------
 */

Route::get('timestamps', 'TransportJobsController@timestamps');

Route::get('transport-jobs/{id}/cancel', 'TransportJobsController@cancel');
Route::get('transport-jobs/{id}/collect', 'TransportJobsController@collect');
Route::get('transport-jobs/close', 'TransportJobsController@close');
Route::post('transport-jobs/close', 'TransportJobsController@setClosed');
Route::get('transport-jobs/pod', 'TransportJobsController@pod');
Route::post('transport-jobs/pod', 'TransportJobsController@setPod');
Route::get('transport-jobs/unmanifested', 'TransportJobsController@unmanifested');
Route::post('transport-jobs/unmanifested', 'TransportJobsController@manifestJobs');
Route::get('transport-jobs/{id}/unmanifest', 'TransportJobsController@unmanifest');
Route::get('transport-jobs/{id}/docket', 'TransportJobsController@docket');
Route::get('transport-jobs/email-dockets', 'TransportJobsController@emailDockets');
Route::resource('transport-jobs', 'TransportJobsController');

/*
  |--------------------------------------------------------------------------
  | Rates
  |--------------------------------------------------------------------------
 */
Route::get('rates/revert', 'RateController@revertCompanyRatesView');
Route::post('rates/revert', 'RateController@revertCompanyRates');
Route::get('rates/{rate}/{rateDate?}', 'RateController@showRate');
Route::get('rates/', 'RateController@index');

Route::get('company-rate/{company}/{service}/download/{date?}', 'RateController@downloadCompanyRate');
Route::get('company-rate/{company}/{service}/upload', 'RateController@uploadCompanyRate');
Route::post('company-rate/{company}/{service}/upload', 'RateController@storeupload');
Route::get('company-rate/{company}/{service}/{discount?}/{date?}', 'RateController@showCompanyRate');

/*
  |--------------------------------------------------------------------------
  | Surcharges
  |--------------------------------------------------------------------------
 */

 Route::get('surcharges', 'SurchargesController@index');

 Route::get('surchargedetails', 'SurchargeDetailsController@index');
 Route::get('surchargedetails/{surcharge}/{company}/index', 'SurchargeDetailsController@index');
 Route::get('surchargedetails/{surcharge}/{company}/create', 'SurchargeDetailsController@create');
 Route::post('surchargedetails/{surcharge}/{company}/create', 'SurchargeDetailsController@store');
 Route::get('surchargedetails/{surcharge}/{company}/edit', 'SurchargeDetailsController@edit');
 Route::patch('surchargedetails/{surcharge}', 'SurchargeDetailsController@update');
 Route::delete('surchargedetails/{surcharge}/{company}/delete', 'SurchargeDetailsController@destroy');

/*
  |--------------------------------------------------------------------------
  | Messages
  |--------------------------------------------------------------------------
 */

 Route::resource('messages', 'MessagesController');
 Route::resource('service-messages', 'ServiceMessagesController');

/*
  |--------------------------------------------------------------------------
  | Company Packaging Types
  |--------------------------------------------------------------------------
 */

Route::get('packaging', 'CompanyPackagingTypesController@index');
Route::get('packaging/dims', 'CompanyPackagingTypesController@dims');

/*
  |--------------------------------------------------------------------------
  | Postcodes
  |--------------------------------------------------------------------------
 */

Route::resource('postcodes', 'PostcodesController');

/*
  |--------------------------------------------------------------------------
  | IFS ND Postcodes
  |--------------------------------------------------------------------------
 */
Route::get('ifs-nd-postcodes', 'PostcodesController@ifsNonDeliveryPostcodes');
Route::post('ifs-nd-postcodes', 'PostcodesController@storeIfsNonDeliveryPostcode');
Route::view('ifs-nd-postcodes/create', 'postcodes/create_ifs_nd_postcode');
Route::delete('ifs-nd-postcodes/{postcode}', 'PostcodesController@deleteIfsNonDeliveryPostcode');

/*
  |--------------------------------------------------------------------------
  | Job queues
  |--------------------------------------------------------------------------
 */
Route::get('jobs', 'JobsController@index');
Route::post('get-jobs', 'JobsController@getJobs');
Route::post('get-failed-jobs', 'JobsController@getFailedJobs');
Route::post('retry-job', 'JobsController@retryJob');
Route::post('retry-all', 'JobsController@retryAll');

Route::get('processes', 'JobsController@processes');
Route::post('get-processes', 'JobsController@getProcesses');

/*
  |--------------------------------------------------------------------------
  | Logs
  |--------------------------------------------------------------------------
 */

Route::get('logs', 'LogsController@index');
Route::post('logs/{log}/get-data', 'LogsController@getData');

/*
  |--------------------------------------------------------------------------
  | Error Logs
  |--------------------------------------------------------------------------
 */
Route::get('error-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('ifsAdmin');

/*
  |--------------------------------------------------------------------------
  | Shipment uploads
  |--------------------------------------------------------------------------
 */
Route::resource('shipment-uploads', 'ShipmentUploadsController');

/*
  |--------------------------------------------------------------------------
  | Import configs
  |--------------------------------------------------------------------------
 */
Route::resource('import-configs', 'ImportConfigsController');
Route::get('import-configs/{importConfig}/download-example', 'ImportConfigsController@downloadExample');

/*
  |--------------------------------------------------------------------------
  | Invalid Commodity Descriptions
  |--------------------------------------------------------------------------
 */
Route::get('invalid-commodity-descriptions', 'InvalidCommodityDescriptionController@index');
Route::post('invalid-commodity-descriptions', 'InvalidCommodityDescriptionController@store');
Route::view('invalid-commodity-descriptions/create', 'invalid_commodity_descriptions/create');
Route::delete('invalid-commodity-descriptions/{description}', 'InvalidCommodityDescriptionController@delete');
