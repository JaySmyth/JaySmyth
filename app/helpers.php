<?php

use App\Models\Company;
use App\Models\Country;
use App\Models\Postcode;
use App\Models\State;
use App\Models\User;
use App\Models\VatCodes;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * Return flash messaging class.
 *
 * @return type
 */
function flash()
{
    return app(\App\Http\Flash::class);
}


/**
 * Formats a UK postcode with a space, 3rd character from end.
 *
 * @param $postcode
 *
 * @return string|string[]
 */
function formatUkPostcode($postcode)
{
    $postcode = preg_replace("/[^A-Z0-9]/", '', strtoupper($postcode));
    if (strlen($postcode) > 3) {
        return substr_replace($postcode, ' ', -3, -strlen($postcode));
    }
}

function isUkEu($senderCountryCode, $recipientCountryCode)
{
    $senderEu = Country::where('country_code', $senderCountryCode)->first()->eu;
    $recipientEu = Country::where('country_code', $recipientCountryCode)->first()->eu;

    if ($senderCountryCode=="GB" && $recipientCountryCode=="GB") {
        return false;
    }

    if ($senderEu && strtoupper($recipientCountryCode) == "GB") {
        return true;
    }

    if ($recipientEu && strtoupper($senderCountryCode) == "GB") {
        return true;
    }

    return false;
}

/**
 * Dump out data for debugging.
 *
 * @param  type  $data
 * @param  type  $msg
 */
function display($data, $msg = '')
{
    echo "$msg<br>";
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

/*
 * Increment a given variable with given value.
 */

function inc(&$var, $value)
{
    if (isset($var)) {
        $var += $value;
    } else {
        $var = $value;
    }
}

/**
 * Un-dot a flattened array.
 *
 * @param  array  $array
 *
 * @return array
 */
function array_undot($array)
{
    foreach ($array as $key => $value) {
        Arr::set($array, $key, $value);
    }

    foreach ($array as $key => $value) {
        if (stristr($key, '.')) {
            unset($array[$key]);
        }
    }

    return $array;
}

function trimData($data)
{
    if (empty($data)) {
        return '';
    }

    if (is_array($data)) {
        return array_map('trimData', $data);
    } else {
        return trim($data);
    }
}

/**
 * Get specific routing value for a given postcode.
 *
 * @param  type  $postcode
 * @param  type  $day
 *
 * @return type
 */
function getRouteValue($postcode, $value = 'collection_route', $day = 1)
{
    $routing = getRouting($postcode, $day);

    if ($routing) {
        return $routing[$value];
    }
}

/**
 * Get collection settings for a postcode.
 *
 * @param  type  $postcode
 * @param  type  $day
 *
 * @return type
 */
function getRouting($postcode, $day = false)
{
    $pc = new Postcode();
    $result = $pc->find($postcode);

    if ($result) {
        return $result->getRouting($day);
    }

    return false;
}

/*
 * Get country name from a 2 character country code.
 *
 * @return string
 */

function getCountry($countryCode)
{
    $country = App\Models\Country::where('country_code', $countryCode)->first();

    if ($country) {
        return $country->country;
    }

    return $countryCode;
}

/**
 * Get country code from country string. Uses string comparison if no match
 * found initially and returns result with the highest similarity.
 *
 * @return string
 */
function getCountryCode($string)
{
    $country = new App\Models\Country();

    return $country->getCode($string);
}

/**
 * Get ANSI state code from given country/state.
 *
 * @param  type  $countryCode
 * @param  type  $state
 *
 * @return type
 */
function getStateCode($countryCode, $state)
{
    $countryCode = strtoupper($countryCode);

    if (($countryCode == 'US' || $countryCode == 'CA') && strlen($state) != 2) {
        return State::getAnsiStateCode($countryCode, $state);
    }

    return $state;
}

/**
 * Get verbose invoice type.
 *
 * @param  type  $type
 *
 * @return string
 */
function verboseInvoiceType($type)
{
    switch ($type) {
        case 'D':
            return 'Duty/Taxes';
        case 'F':
            return 'Freight';
        default:
            return 'n/a';
    }
}

/**
 * Get verbose invoice type.
 *
 * @param  type  $type
 *
 * @return string
 */
function verboseImportExport($importExport)
{
    switch ($importExport) {
        case 'I':
            return 'Import Invoice';
        case 'E':
            return 'Export Invoice';
        default:
            return 'n/a';
    }
}

/**
 * Get verbose invoice type.
 *
 * @param  type  $type
 *
 * @return string
 */
function verboseCollectionDelivery($value)
{
    switch (strtoupper($value)) {
        case 'C':
            return 'Collection';
        case 'D':
            return 'Delivery';
        default:
            return;
    }
}

/*
 *
 */

function getVolumetricDivisor($carrierCode, $serviceCode)
{
    $carrier = App\Models\Carrier::where('code', $carrierCode)->first();

    if ($carrier) {
        return $divisor = $carrier->services->where('code', $serviceCode)->first()->volumetric_divisor;
    }

    return 0;
}

/*
 *
 */

function getCarrierName($carrierId)
{
    $carrier = App\Models\Carrier::find($carrierId);

    if ($carrier) {
        return $carrier->name;
    }

    return $carrierId;
}

/**
 * Get pickup time.
 *
 * @param  string  $countryCode
 * @param  string  $postcode
 *
 * @return string
 */
function getPickupTime($countryCode, $postcode)
{
    $pickupTime = new Postcode();

    return $pickupTime->getPickupTime($countryCode, $postcode);
}

function cleanRegex($regex)
{
    return $regex;

    $regex = trim($regex, '/^$');
    $regex = str_replace('?', '', $regex);
    $regex = str_replace('!', ' not ', $regex);
    $regex = str_replace('$', '', $regex);
    $regex = str_replace('[A-Z0-9 ]{3,6}', '', $regex);
    return $regex;
}

/**
 * This function will return an array that can be used to populate a select drop down.
 * The function can be called directly from within a blade view.
 *
 * @param  string dropDown   the dropdown to generate
 * @param  string prepend    prepend to the array with a default value
 * @param  int modeID    mode specific results
 *
 * @return array
 */
function dropDown($dropDown, $prepend = null, $modeId = null)
{
    switch ($dropDown) {
        case 'companies':
            $result = App\Models\Company::select('id', 'company_name')->orderBy('company_name')->pluck('company_name', 'id');
            break;
        case 'invoiceable':
            $result = App\Models\Company::where('legacy_pricing', false)->pluck('company_name', 'id')->toArray();
            break;
        case 'sites':
            $result = Auth::user()->sites();
            break;
        case 'enabledSites':
            $result = Auth::user()->sites(1);
            break;
        case 'enabledModes':
            $result = Auth::user()->modes()->pluck('label', 'id');
            break;
        case 'associatedDepots':
            $result = Auth::user()->depots()->pluck('name', 'id');
            break;
        case 'countries':
            $result = App\Models\Country::select('country', 'country_code')->orderBy('country')->pluck('country', 'country_code');
            break;
        case 'senderCountries':
            $result = ['GB' => 'UNITED KINGDOM', 'IE' => 'IRELAND', 'US' => 'UNITED STATES', 'CA' => 'CANADA'];
            break;
        case 'currencies':
            $result = App\Models\Currency::select('code')->orderBy('display_order')->pluck('code', 'code');
            break;
        case 'hazards':
            $result = App\Models\Hazard::where('mode_id', $modeId)->pluck('description', 'code');
            break;
        case 'terms':
            $result = App\Models\Term::where('mode_id', $modeId)->pluck('description', 'code');
            break;
        case 'salesrates':
            $result = App\Models\Rate::where('rate_type', 's')->orderBy('description')->pluck('description', 'id');
            break;
        case 'roles':
            $result = App\Models\Role::wherePrimary(1)->pluck('label', 'id');
            break;
        case 'roles_customer':
            $result = App\Models\Role::wherePrimary(1)->whereIfsOnly(0)->pluck('label', 'id');
            break;
        case 'roles_ifs':
            $result = App\Models\Role::wherePrimary(1)->whereIfsOnly(1)->pluck('label', 'id');
            break;
        case 'printFormats':
            $result = App\Models\PrintFormat::select('name', 'id')->pluck('name', 'id');
            break;
        case 'depots':
            $result = App\Models\Depot::select('name', 'id')->pluck('name', 'id');
            break;
        case 'manifestProfiles':
            $result = App\Models\ManifestProfile::select('name', 'id')->pluck('name', 'id');
            break;
        case 'departments':
            $result = App\Models\Department::select('id', DB::raw('CONCAT(name, " (", code, ")") AS department'))
                ->orderBy('department')->pluck('department', 'id');
            break;
        case 'surchargeCategories':
            $result = App\Models\Surcharge::select('name', 'id')->pluck('name', 'id');
            break;
        case 'modes':
            $result = App\Models\Mode::select('label', 'id')->pluck('label', 'id');
            break;
        case 'sales':
            $result = App\Models\Sale::select('name', 'id')->pluck('name', 'id');
            break;
        case 'localisations':
            $result = App\Models\Localisation::select('time_zone', 'id')->pluck('time_zone', 'id');
            break;
        case 'statuses':
            $result = App\Models\Status::select('name', 'id')->where('id', '>', 0)
                ->where('id', '<', 13)->orWhereIn('id', [19,20,21])->pluck('name', 'id')->toArray();
            $result = Arr::add($result, 'S', 'Shipped (All except cancelled)');
            break;
        case 'statusCodes':
            $result = [
                'pre_transit' => 'Pre-Transit', 'no_goods' => 'No Goods Received', 'cancelled' => 'Shipment Cancelled', 'received' => 'Goods Received', 'in_transit' => 'In Transit',
                'out_for_delivery' => 'Out For Delivery', 'partial_delivery' => 'Partial Delivery', 'delivered' => 'Delivered', 'on_hold' => 'On Hold',
                'failure' => 'Failure', 'return_to_sender' => 'Return To Sender', 'rts_complete' => 'RTS Complete', 'claim' => 'Claim',
            ];
            break;
        case 'uoms':
            $result = App\Models\Uom::select('name', 'code')->orderBy('name')->pluck('name', 'code');
            break;
        case 'packagingTypes':
            if (isset($modeId)) {
                $result = App\Models\PackagingType::select('name', 'code')->where('mode_id', $modeId)->pluck('name', 'code');
            } else {
                $result = App\Models\PackagingType::select('name', 'code')->pluck('name', 'code');
            }
            break;
        case 'carriers':
            $result = App\Models\Carrier::select('name', 'id')->pluck('name', 'id');
            break;
        case 'shippingLines':
            $result = App\Models\ShippingLine::select('name', 'id')->pluck('name', 'id');
            break;
        case 'routes':
            $result = App\Models\Route::select('name', 'id')->pluck('name', 'id');
            break;
        case 'services':
            $result = App\Models\Service::select('name', 'code')->orderBy('name')->groupBy('name')->pluck('name', 'code');
            break;
        case 'uniqueServices':
            $result = App\Models\Service::select('name', 'code')->orderBy('name')->groupBy('name')->pluck('name', 'code');
            break;
        case 'seaFreightStatuses':
            $result = App\Models\SeaFreightStatus::select('name', 'id')->orderBy('id')->pluck('name', 'id');
            $result = Arr::add($result, 'active', 'Active (all except delivered/cancelled)');
            break;
        case 'serviceCodes':
            $result = App\Models\Service::orderBy('code')->pluck('code', 'code');
            break;
        case 'serviceIds':
            // $result = App\Models\Service::select('carrier_name', 'id')->orderBy('carrier_name')->pluck('carrier_name', 'id');
            $result = App\Models\Service::select('id', DB::raw('CONCAT(name, "/ ", carrier_name) AS carrier_name'))
                ->orderBy('carrier_name')->pluck('carrier_name', 'id');
            break;
        case 'shipReasons':
            $result = App\Models\ShipReason::orderBy('id')->pluck('description', 'code');
            break;
        case 'cpc':
            $result = App\Models\CustomsProcedureCode::select('code', 'id')->orderBy('code')->pluck('code', 'id');
            break;
        case 'vehicles':
            $result = App\Models\Vehicle::select('id', DB::raw('CONCAT(registration, " (", type, ")") AS vehicle'))
                ->orderBy('vehicle')->pluck('vehicle', 'id');
            break;
        case 'drivers':
            $result = App\Models\Driver::select('name', 'id')->whereEnabled(1)->orderBy('name')->pluck('name', 'id');
            break;
        case 'vehicleTypes':
            $result = ['17 Tonne' => '17 Tonne', '40ft' => '40ft', 'Transit' => 'Transit'];
            break;
        case 'invoiceStatuses':
            $result = ['U' => 'Unprocessed', '0' => 'Match Failed', '1' => 'Passed', '2' => 'Processed'];
            break;
        case 'type':
            $result = ['c' => 'Commercial Address', 'r' => 'Residential Address'];
            break;
        case 'surchargeType':
            $result = ['s' => 'IFS Supplier', 'i' => 'IFS Customer'];
            break;
        case 'testing':
            $result = ['0' => 'Live', '1' => 'Testing'];
            break;
        case 'enabled':
            $result = ['1' => 'Enabled', '0' => 'Disabled'];
            break;
        case 'boolean':
            $result = [1 => 'Yes', 0 => 'No'];
            break;
        case 'domestic':
            $result = [1 => 'Domestic Only', 0 => 'International Only'];
            break;
        case 'delimiters':
            $result = ['comma' => 'Comma', 'tab' => 'Tab'];
            break;
        case 'labelCopies':
            $result = [0 => 'None', 1 => '1 Extra Copy', 2 => '2 Extra Copies'];
            break;
        case 'rateTypes':
            $result = ['all' => 'All Rates', 'domestic' => 'Domestic Rates', 'intl' => 'International Rates'];
            break;
        case 'weightUom':
            $result = ['kg' => 'kg', 'lb' => 'lb'];
            break;
        case 'fileFormats':
            $result = [
                'csv' => 'Csv', 'xls' => 'Excel (xls)', 'xlsx' => 'Excel (xlsx)', 'html' => 'HTML (no attachments)',
            ];
            break;
        case 'frequency':
            $result = ['daily' => 'Daily', 'twiceDaily' => 'Twice Daily'];
            break;
        case 'containerSizes':
            $result = [
                '20ft' => '20ft Container', '40ft' => '40ft Container', '45ft' => '45ft Container',
                '40ft High Cube' => '40ft High Cube Container',
            ];
            break;
        case 'traffic':
            $result = [
                'D' => 'Domestic (same country)', 'EU' => 'European Union', 'ED' => 'EU Excluding UK Domestic',
                'I' => 'International (all non UK Domestic)', 'N' => 'Non EU (everything outside of EU)',
                'UD' => 'UK Domestic',
            ];
            break;
        case 'numeric':
            $result = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10];
            break;
        case 'dates':
            $result = getDates();
            break;
        case 'datesLong':
            $result = getDates('-4 months', '+ 1 year');
            break;
        case 'datesLongFuture':
            $result = getDates('today', '+ 400 days');
            break;
        case 'datesShort':
            $result = getDates('- 1 day', '+ 14 days');
            break;
        case 'datesPresent':
            $result = getDates('-2 months', 'today');
            break;
        case 'datesFuture':
            $result = getDates('today', '+1 month');
            break;
        case 'times':
            $result = getTimes();
            break;
        case 'monthsPrevious':
            for ($i = 0; $i <= 24; $i++) {
                $result[] = date('F Y', strtotime(date('Y-m-01')." -$i months"));
            }
            $result = array_combine($result, $result);
            break;
        case 'importConfigFields':
            $result = App\Models\ImportConfigFields::orderBy('display_order')->pluck('description', 'name');
            break;
        case 'importConfigs':
            $result = Auth::user()->getImportConfigs()->pluck('company_name', 'id');
            break;
        case 'jobType':
            $result = ['c' => 'Collection', 'd' => 'Delivery'];
            break;
        case 'jobStatuses':
            $result = [13 => 'Unmanifested', 14 => 'Manifested', 15 => 'Completed', 7 => 'Cancelled'];
            break;
        case 'standardSalesRates':
            $result = App\Models\Rate::where('rate_type', 's')->where('id', '<', '1000')
                ->orderBy('description')->pluck('description', 'id');
            break;
        case 'scsChargeCodes':

            $result = [
                'AAR' => 'AAR - Alternative Address Request',
                'ADF' => 'ADF - Address correction',
                'ADH' => 'ADH - Additional handling',
                'ADM' => 'ADM - Admin Fee',
                'BSO' => 'BSO - Broker Selection Option',
                'CDV' => 'CDV - Duty/Vat',
                'CLR' => 'CLR – Clearance Charges',
                'DDF' => 'DDF - Duties & Taxes Paid Admin Fee',
                'DIF' => 'DIF - Dry Ice Fee',
                'DVF' => 'DVF - Declared Value Fee',
                'EAS' => 'EAS - Extended Area Surcharge',
                'ERF' => 'ERF - Elevated Risk Fee',
                'ESS' => 'ESS - Emergency Shipment Surcharge',
                'EVF' => 'EVF - Exporter Validation Fee',
                'FRT' => 'FRT - Freight',
                'FSC' => 'FSC - Fuel Surcharge',
                'HAZ' => 'HAZ - Hazardous Surcharge',
                'INS' => 'INS - Insurance',
                'LPS' => 'LPS - Large Package Surcharge',
                'MIS' => 'MIS - Miscellaneous Charge',
                'OOA' => 'OOA - Out of Delivery Area',
                'OSS' => 'OSS - Oversize Surcharge',
                'RAS' => 'RAS - Remote Area Surcharge',
                'RES' => 'RES - Residential Surcharge',
                'RTN' => 'RTN - Return Goods Fee',
                'SMS' => 'SMS - SMS Alerts',
            ];
            break;
        case 'ifsChargeCodes':
            $result = App\Models\ChargeCodes::orderBy('code')->orderBy('description')->pluck('description', 'code');
            break;
        case 'surchargeCodes':
            $codes = App\Models\Surcharge::select('code', 'name')->distinct()->get();
            foreach ($codes as $value) {
                $result[$value->code] = $value->name;
            }
            break;
        case 'carrierChoice':
            $result = ['cost' => 'Cost to IFS', 'sales' => 'Cost to customer', 'user' => 'User selection', 'debug' => 'Debug'];
            break;
        default:
            $result = [];
            break;
    }

    if ($prepend) {
        if (is_array($result)) {
            $result = ['' => $prepend] + $result;
        } else {
            $result = $result->prepend($prepend, '');
        }
    }

    return $result;
}

/**
 * Return an array of dates.
 *
 * @param  string  $format
 *
 * @return array
 */
function getDates($start = '-6 months', $finish = '+3 months', $format = 'd-m-Y')
{
    $start = strtotime($start);
    $finish = strtotime($finish);

    $i = 0;

    do {
        $currentDate = $start + (86400 * $i);
        $date = date($format, $currentDate);
        $dates[$date] = $date;
        $i++;
    } while ($currentDate < $finish);

    return $dates;
}

/**
 * Return an arrray of times.
 *
 * @param  type  $interval
 *
 * @return array
 */
function getTimes($interval = 1)
{
    for ($hours = 0; $hours < 24; $hours++) {
        for ($mins = 0; $mins < 60; $mins += $interval) {
            $time = str_pad($hours, 2, '0', STR_PAD_LEFT).':'.str_pad($mins, 2, '0', STR_PAD_LEFT);
            $times[$time] = $time;
        }
    }

    return $times;
}

/**
 * Gets verbose address type.
 *
 * @param  type  $type
 *
 * @return string
 */
function getAddressType($type)
{
    if ($type == 'r') {
        return 'Residential Address';
    }

    return 'Commercial Address';
}

/*
 *
 */

function getDateFormat($format)
{
    switch ($format) {
        case 'yyyy-mm-dd':
        case 'yyyy/mm/dd':
            return 'Y-m-d';
            break;
        case 'dd-mm-yyyy':
        case 'dd/mm/yyyy':
            return 'd-m-Y';
            break;
        case 'mm-dd-yyyy':
        case 'mm/dd/yyyy':
            return 'm-d-Y';
            break;
        default:
            return;
            break;
    }
}

/*
 * Human readable bytes conversion
 */

function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision).' '.$units[$pow];
}

/*
 * Check if table is already joined in Laravel Query Builder.
 */

function isJoined($query, $table)
{
    $joins = $query->getQuery()->joins;
    if ($joins == null) {
        return false;
    }
    foreach ($joins as $join) {
        if ($join->table == $table) {
            return true;
        }
    }

    return false;
}

/**
 * Returns an array of countries that IFS deam to be domestic.
 *
 * @return array
 */
function getUkDomesticCountries()
{
    return ['GB', 'GG', 'JE', 'IM'];
}

/**
 * Determine if a country code is UK domestic.
 *
 * @param  type  $recipientCountryCode
 *
 * @return bool
 */
function isUkDomestic($countryCode)
{
    return in_array(strtoupper($countryCode), getUkDomesticCountries());
}

/**
 * Determines if a shipment is classified as domestic.
 *
 * @param  type  $recipientCountryCode
 *
 * @return bool
 */
function isDomestic($senderCountryCode, $recipientCountryCode)
{
    // Shipping within the same country
    if ($senderCountryCode == $recipientCountryCode) {
        return true;
    }

    // Shipping within the UK
    if (isUkDomestic($senderCountryCode) && isUkDomestic($recipientCountryCode)) {
        return true;
    }

    return false;
}


/**
 * Determine if a country id within the EU.
 *
 * @param $countryCode
 *
 * @return mixed
 */
function isEu($countryCode)
{
    $country = Country::where('country_code', $countryCode)->first();

    if ($country) {
        return $country->eu;
    }
}

/**
 * NI Shipment - BT to BT postcode.
 *
 * @param $senderPostcode
 * @param $recipientPostcode
 *
 * @return bool
 */
function isNiShipment($senderPostcode, $recipientPostcode)
{
    return isBtPostcode($senderPostcode) && substr(strtoupper($senderPostcode), 0, 2) === substr(strtoupper($recipientPostcode), 0, 2);
}

/**
 * Shipment from NI to EU.
 *
 * @param $senderPostcode
 * @param $recipientCountryCode
 *
 * @return bool
 */
function isNiToEu($senderPostcode, $recipientCountryCode)
{
    return isBtPostcode($senderPostcode) && isEu($recipientCountryCode);
}


/**
 * GB to NI shipment.
 *
 * @param $senderCountryCode
 * @param $recipientCountryCode
 * @param $senderPostcode
 * @param $recipientPostcode
 *
 * @return bool
 */
function isGbToNi($senderCountryCode, $recipientCountryCode, $senderPostcode, $recipientPostcode)
{
    // Shipping within the UK
    if (isUkDomestic($senderCountryCode) && strtoupper($recipientCountryCode) == 'GB') {
        // Sender postcode not BT - recipient postcode is BT
        if (! isBtPostcode($senderPostcode) && isBtPostcode($recipientPostcode)) {
            return true;
        }
    }

    return false;
}

/**
 * Check if postcode starts with BT.
 *
 * @param $postcode
 *
 * @return bool
 */
function isBtPostcode($postcode)
{
    return substr(strtoupper(trim($postcode)), 0, 2) == 'BT';
}

/**
 * Convert y/n to boolean.
 *
 * @param  string  $value
 *
 * @return bool
 */
function yesNoToBoolean($value)
{
    if (strtoupper($value) == 'Y') {
        return 1;
    }

    return 0;
}

/**
 * convert boolean to y/n.
 *
 * @param  mixed  $value
 *
 * @return string
 */
function booleanToYn($value)
{
    if ($value) {
        return 'Y';
    }

    return 'N';
}

/**
 * Accepts a string or array and attempts to guess
 * the encoding used. It then converts the string
 * or array to UTF-8 encoding and returns the same
 * datatype as received.
 *
 * @param  mixed  $data  (string or array)
 *
 * @return mixed in UTF-8 encoding
 */
function convertToUTF8($data)
{
    if (is_array($data)) {
        array_walk_recursive($data, function (&$item, $key) {
            $charSet = mb_detect_encoding($item, ['UTF-8', 'ISO-8859-1', 'Windows-1251'], false);
            if ($charSet != 'UTF-8') {
                $item = iconv($charSet, 'UTF-8', $item);
            }
        });
    } else {
        $charSet = mb_detect_encoding($data, ['UTF-8', 'ISO-8859-1', 'Windows-1251'], false);
        if ($charSet != 'UTF-8') {
            $data = iconv($charSet, 'UTF-8', $data);
        }
    }

    return $data;
}

/**
 * Calculate the checksum digit from provided number.
 *
 * @param $number
 *
 * @return int
 */
function mod10CheckDigit($number)
{
    $matrix = [
        [0, 3, 1, 7, 5, 9, 8, 6, 4, 2],
        [7, 0, 9, 2, 1, 5, 4, 8, 6, 3],
        [4, 2, 0, 6, 8, 7, 1, 3, 5, 9],
        [1, 7, 5, 0, 9, 8, 3, 4, 2, 6],
        [6, 1, 2, 3, 0, 4, 5, 9, 7, 8],
        [3, 6, 7, 4, 2, 0, 9, 5, 8, 1],
        [5, 8, 6, 9, 7, 2, 0, 1, 3, 4],
        [8, 9, 4, 5, 3, 6, 2, 0, 1, 7],
        [9, 4, 3, 8, 6, 1, 7, 2, 0, 5],
        [2, 5, 8, 1, 4, 3, 6, 7, 9, 0],
    ];

    /* @var $interim int */
    $interim = 0;

    /* @var $i int */
    for ($i = 0; $i < strlen($number); $i++) {
        $interim = $matrix[$interim][substr($number, $i, 1)];
    }

    return $interim;
}

/**
 * Calculate the modulo 11 check digit from provided number.
 *
 * @param $number
 *
 * @return int
 */
function mod11CheckDigit($number)
{
    $weight = [8, 6, 4, 2, 3, 5, 9, 7];
    $sum = 0;

    for ($i = 0; $i < strlen($number); $i++) {
        $digit = (int) substr($number, $i, 1);
        $sum += ($digit * $weight[$i]);
    }

    // Mod 11
    $remainder = $sum % 11;

    // Subtract the remainder from 11
    $result = 11 - $remainder;

    // – if the result falls within the range 1 to 9, use the result as the check digit;
    // – if the result is 10, use 0 as the check digit;
    // – if the result is 11, use 5 as the check digit.

    switch ($result) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
            return $result;
        case 10:
            return 0;
        case 11:
            return 5;
        default:
            return 0;
    }
}

/**
 * Checks the checksum digit from provided number.
 *
 * @param $number
 *
 * @return bool
 */
function checkMod10($number)
{
    return 0 == mod10CheckDigit($number);
}

/**
 * Convert snake case to space separated words.
 *
 * @param  string  $value
 *
 * @return string
 */
function snakeCaseToWords($value)
{
    return ucwords(str_replace('_', ' ', $value));
}

/**
 * Return carbon instance from a string or timestamp. Defaults to current timestamp if
 * the datetime cannot be parsed.
 *
 * @param  type  $datetime
 * @param  type  $timezone
 *
 * @return Carbon
 */
function toCarbon($datetime)
{
    if ($datetime instanceof Carbon) {
        return $datetime;
    }

    $newDatetime = Carbon::parse($datetime);

    if (! $newDatetime) {
        $newDatetime = Carbon::now();
    }

    return $newDatetime;
}

/**
 * Return carbon instance from a string or timestamp. Defaults to current timestamp if
 * the datetime cannot be parsed.
 *
 * @param  type  $datetime
 * @param  type  $timezone
 *
 * @return Carbon
 */
function gmtToCarbonUtc($datetime)
{
    $datetime = toCarbon($datetime);

    return $datetime->subHour(1);
    //return $datetime;
}

/**
 * Gets a timezone from country and state. Currently works for US and Canada and all countries with
 * a single timezone. For countries with multiple timezones, a best guess is made. Could be developed
 * further for more accuracy using a city/region lookup. Defaults to "Europe/London".
 *
 * @param  type  $countryCode
 * @param  type  $state
 * @param  type  $city  (currently not used)
 *
 * @return string
 */
function getTimezone($countryCode, $state = false, $city = false)
{
    $countryCode = getCountryCode($countryCode);

    if (! $countryCode) {
        return 'Europe/London';
    }

    $state = strtoupper($state);
    $city = strtoupper($city);
    $timezone = false;

    // Get timezone using country code and state for Canada/US
    if ($countryCode == 'CA' || $countryCode == 'US') {
        $timezone = geoip_time_zone_by_country_and_region($countryCode, $state);
    }

    // Get timezone using country code
    if (! $timezone) {
        $countryTimezones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $countryCode);

        if (count($countryTimezones) > 0) {
            $timezone = last($countryTimezones);
        }
    }

    // Return the timezone
    if ($timezone) {
        return $timezone;
    }

    // Default to London
    return 'Europe/London';
}

/**
 * Determine what VAT is liable and return the
 * amount and relevant vat_code.
 *
 * @param  string  $countryCode
 * @param  decimal  $valueOfGoods
 * @param  bool  $vatExempt
 *
 * @return string
 */
function calcVat($countryCode, $valueOfGoods, $vatExempt)
{


    // If Recipient country is GB or in the EU
    $countryCode = strtoupper($countryCode);
    $eu = Country::where('country_code', $countryCode)->first()->eu;
    if (in_array($countryCode, ['GB', 'IM'])) {  // 'GB', 'GG', 'JE', 'IM'

        // Recipient is in EU, GB or we are shipping to Channel Islands
        if ($vatExempt) {
            // Goods are Exempt
            $vatDetails['vat_amount'] = 0;
            $vatDetails['vat_code'] = 'Z';
        } else {
            $percent = (float) VatCodes::where('code', '1')->first()->percent;

            // Goods are not Exempt
            if ($valueOfGoods > 0) {
                $vatDetails['vat_amount'] = round(($valueOfGoods * $percent) / 100, 2);
                $vatDetails['vat_code'] = '1';
            } else {
                $vatDetails['vat_amount'] = 0;
                $vatDetails['vat_code'] = '1';
            }
        }
    } else {
        // No VAT due
        $vatDetails['vat_amount'] = 0;
        $vatDetails['vat_code'] = 'Z';
    }

    return $vatDetails;
}

/**
 * Determine if customs entry required.
 *
 * @param $senderCountryCode
 * @param $recipientCountryCode
 * @param $senderPostcode
 * @param $recipientPostcode
 *
 * @return bool
 */
function customsEntryRequired($senderCountryCode, $recipientCountryCode, $senderPostcode, $recipientPostcode, $companyId = null)
{
    $senderCountryCode = getCountryCode($senderCountryCode);
    $recipientCountryCode = getCountryCode($recipientCountryCode);

    // Blind company LT - sender postcode is always BT (although shipped from GB)
    if ($companyId == 1015 && substr(strtoupper($recipientPostcode), 0, 2) == 'BT') {
        return true;
    }

    // GB to NI
    if (isGbToNi($senderCountryCode, $recipientCountryCode, $senderPostcode, $recipientPostcode)) {
        return true;
    }

    // If Shipping within the same country
    if ($senderCountryCode == $recipientCountryCode) {
        return false;
    }

    // Check if Both Countries in the EU
    /*
    $fromEU = false;
    $toEU = false;
    $country = App\Models\Country::where('country_code', $senderCountryCode)->first();
    if ($country) {
        $fromEU = $country->eu;
    }

    $country = App\Models\Country::where('country_code', $recipientCountryCode)->first();
    if ($country) {
        $toEU = $country->eu;
    }

    if ($fromEU && $toEU) {
        return false;
    }
    */

    return true;
}

/**
 * Shipment within EU.
 *
 * @param $senderCountryCode
 * @param $recipientCountryCode
 *
 * @return bool
 */
function isWithinEu($senderCountryCode, $recipientCountryCode)
{
    $sender = Country::where('country_code', $senderCountryCode)->first()->eu;
    $recipient = Country::where('country_code', $recipientCountryCode)->first()->eu;

    if ($sender && $recipient) {
        return true;
    }

    return false;
}

/**
 * Identify direction of travel.
 *
 * @param  type  $shipment
 *
 * @return string Direction
 */
function identifyDirection($shipment)
{
    $customer_country_code = Company::find($shipment['company_id'])->country_code;

    // Shipper and Recipient in UK
    if ($shipment['sender_country_code'] == $shipment['recipient_country_code'] && $shipment['sender_country_code'] == 'GB') {
        if ((substr($shipment['sender_postcode'], 0, 2) == 'BT') && (substr(
            $shipment['recipient_postcode'],
            0,
            2
        ) == 'BT')) {
            $direction = 'internal';
        } elseif ((substr($shipment['sender_postcode'], 0, 2) == 'BT') && (substr(
            $shipment['recipient_postcode'],
            0,
            2
        ) != 'BT')) {
            $direction = 'export';
        } else {
            $direction = 'import';
        }
    } else {
        $direction = getDirection(
            $customer_country_code,
            $shipment['sender_country_code'],
            $shipment['recipient_country_code']
        );
    }

    return $direction;
}

/**
 * @param  type  $homeCountry
 * @param  type  $fromCountry
 * @param  type  $toCountry
 *
 * @return string
 */
function getDirection($homeCountry, $fromCountry, $toCountry)
{
    if ($fromCountry == $toCountry) {
        return 'internal';
    } elseif ($homeCountry == $fromCountry) {
        return 'export';
    } elseif ($homeCountry == $toCountry) {
        return 'import';
    } else {
        return 'unknown';
    }
}

/**
 * @param  type  $shipment
 *
 * @return string
 */
function identifyDepartment($shipment)
{
    $direction = identifyDirection($shipment);

    if ($direction == 'unknown') {
        return 'unknown';
    }

    switch ($shipment['mode']) {
        case 'courier':

            /*
            // IE to IE shipment
            if (strtoupper($shipment['sender_country_code']) == 'IE' && strtoupper($shipment['recipient_country_code']) == 'IE') {
                return $direction == 'import' ? 'IFCUK' : 'IFCUK';
            }

            // IE to UK Domestic Shipment
            if (strtoupper($shipment['sender_country_code']) == 'IE' && isUkDomestic($shipment['recipient_country_code'])) {
                return $direction == 'import' ? 'IFCUK' : 'IFCUK';
            }

            // IE to International
            if (strtoupper($shipment['sender_country_code']) == 'IE') {
                return $direction == 'import' ? 'IFCIM' : 'IFCEX';
            }
            */

            // Is it an NI to IE shipment
            if (isBtPostcode($shipment['sender_postcode']) && strtoupper($shipment['recipient_country_code']) == 'IE') {
                return $direction == 'import' ? 'IFCUK' : 'IFCUK';
            }
            // Is it a Domestic Shipment
            if (isUkDomestic($shipment['sender_country_code']) && isUkDomestic($shipment['recipient_country_code'])) {
                return $direction == 'import' ? 'IFCUK' : 'IFCUK';
            }

            // Must be an International Shipment
            return $direction == 'import' ? 'IFCIM' : 'IFCEX';
            break;

        case 'seafreight':
            return $direction == 'import' ? 'IFFSI' : 'IFFSX';
            break;

        case 'airfreight':
            return $direction == 'import' ? 'IFFAI' : 'IFFAX';
            break;

        case 'roadfreight':
            if ($direction == 'import') {
                $department = 'IFFRI';
            }

            if ($direction == 'export') {
                $department = 'IFFRX';
            }

            if ($direction == 'internal') {
                $department = 'IFFRD';
            }
            break;

        default:
            $department = 'unknown';
            break;
    }

    return $department;
}

/**
 * Obtain value from json encoded data.
 *
 * @param  string  $json
 * @param  string  $key
 *
 * @return mixed
 */
function getValueFromJson($json, $key)
{
    $array = json_decode($json, true);

    if (! empty($array[$key])) {
        return $array[$key];
    }
}

/**
 * * Determines what CSS class to use for shipment tracking progress.
 *
 * @param  type  $progress
 * @param  type  $percentage
 *
 * @return string
 */
function getTrackingState($progress, $percentage)
{
    if ($progress > $percentage) {
        return 'past-state';
    } elseif ($progress == $percentage) {
        return 'current-state';
    } else {
        return 'future-state';
    }
}

/**
 * Determines what colour to use for the progress bar on the tracking page.
 *
 * @param  type  $statusCode
 *
 * @return string
 */
function getProgressBarColour($statusCode)
{
    switch ($statusCode) {
        case 'cancelled':
        case 'claim':
        case 'failure':
        case 'unknown':
            return 'red';
        case 'saved':
            return 'yellow';
        case 'on_hold':
        case'return_to_sender':
            return 'orange';
        case 'delivered':
            return 'green';
        default:
            return 'blue';
    }
}

/**
 * Get Next available number in a sequence,
 * locking records to prevent duplication.
 *
 * @param  type  $sequenceType
 *
 * @return Next Available number
 */
function nextAvailable($sequenceType)
{
    $seq = 0;

    /*
     * *********************************************************
     * Bracket as transaction and lock record within a callback
     *
     * Note: lock only works within a transaction
     * *********************************************************
     */
    try {
        app('db')->transaction(function () use ($sequenceType, &$seq) {
            $record = App\Models\Sequence::whereCode($sequenceType)->lockForUpdate()->first();
            $seq = $record->getNextAvailable();
        });
    } catch (Exception $e) {
        dd($e);
    }

    return $seq;
}

/**
 * Write a csv file to given path.
 *
 * @param  type  $path
 * @param  type  $data
 * @param  type  $mode
 */
function writeCsv($path, $data, $mode = 'w', $delimiter = ',')
{
    $handle = fopen($path, $mode);
    foreach ($data as $row) {
        fputcsv($handle, $row, $delimiter);
    }
    fclose($handle);

    if (file_exists($path)) {
        chmod($path, 777);

        return $path;
    }
}

/**
 * An array of excel column names.
 *
 * @param  int  $numberOfColumns
 *
 * @return array
 */
function getExcelColumNames($numberOfColumns = 52)
{
    $r = null;
    $prefix = null;
    for ($n = 0; $n < $numberOfColumns; $n++) {
        if ($n == 26) {
            $prefix = 'A';
        }

        $r = $prefix.chr($n % 26 + 0x41);
        $columns[] = $r;
    }

    return $columns;
}

// *********************************************************************************************************************************************************** //
// **************************************************************** LEGACY CONVERSION FUNCTIONS ************************************************************ //
// ********************************************************* THESE CAN BE REMOVED ONCE DATA MIGRATED *********************************************************
// *********************************************************************************************************************************************************** //

/*
 * Find an IFS user ID from a string
 */
function findIfsUserId($string)
{
    $user = User::select('id')->filter($string)->restrictByCompany([4])->first();

    if ($user) {
        return $user->id;
    }

    return 0;
}

/*
 * Get the legacy print format code.
 */

function getLegacyPrintFormat($printFormatCode)
{
    switch ($printFormatCode) {
        case '6X4':
            return 'label6x4';
        case '6-6X4':
        case 'FEDEX':
            return 'labelFDX';
        default:
            return 'A4';
    }
}

/*
 * Get legacy testing value.
 */

function getLegacyTestingStatus($testing)
{
    if ($testing) {
        return 'T';
    }

    return 'L';
}

function convertBillToCountryToLegacy($billTo, $senderCountryCode, $recipientCountryCode)
{
    switch ($billTo) {
        case 'sender':
            return $senderCountryCode;
            break;

        default:
            return $recipientCountryCode;
            break;
    }
}

function convertLegacyBillTo($billTo)
{
    switch ($billTo) {
        case 'SP':
            return 'sender';
        case 'RP':
            return 'recipient';
        case 'SO':
        case 'RO':
            return 'other';
    }
}

function convertBillToToLegacy($billTo)
{
    switch ($billTo) {
        case 'sender':
            return 'SP';
        case 'recipient':
            return 'RP';
        case 'other':
            return 'SO';
    }
}

function convertUnitsToLegacy($units)
{
    switch ($units) {
        case 'kg':
            return 'KGS';
        case 'lb':
            return 'LBS';
    }
}

function convertLegacyDelivered($complete, $podFlag, $createdAt)
{
    if ($podFlag) {
        return true;
    }

    $createdAt = strtotime($createdAt);
    $cutOff = strtotime('-3 weeks');

    if ($complete == 'Y' && $createdAt < $cutOff) {
        return true;
    }

    return false;
}

function convertLegacyStatus($complete, $delivered, $received, $hold, $createdAt)
{
    if ($delivered) {
        return 6; // Delivered
    }

    if ($complete == 'C') {
        return 7; // cancelled
    }

    $createdAt = gmtToCarbonUtc($createdAt);

    if ($received && $createdAt->isToday()) {
        return 3; // Received
    }

    if ($hold) {
        return 8; // hold
    }

    switch ($complete) {
        case 'Y':
            return 4; // in transit

        case 'N':
            if ($received) {
                return 4; // in_transit
            }

            return 2; // pre_transit

        case 'S':
            return 1; // saved

        default:
            return 2; // pre_transit
    }
}

function convertLegacyMode($consignmentNumber)
{
    return 1;
}

function convertLegacyDepartment($consignmentNumber, $senderCountry, $recipientCountry)
{
    if (stristr($consignmentNumber, 'AIR')) {
        return 7;
    }

    if (! isUkDomestic($senderCountry) && isUkDomestic($recipientCountry)) {
        return 2;
    }

    return 1;
}

function convertLegacyRoute($route, $depot)
{
    if ($depot == 'NYC') {
        return 3;
    }

    if ($route == 'BFS') {
        return 2;
    }

    return 1;
}

function convertLegacyDepot($depot)
{
    switch ($depot) {
        case 'ANT':
            return 1;
        case 'LON':
            return 2;
        case 'NYC':
            return 3;
        default:
            return 1;
    }
}

/**
 * needs correct service Ids applied.
 *
 * @param  type  $service
 *
 * @return int
 */
function convertLegacyService($service, $gateway)
{
    switch ($gateway) {
        case 'FXRS':
            switch ($service) {
                case '01':
                    return '10';
                case '03':
                    return '9';     // not in services table
                case '04':
                    return '10';    // Default to IP
                case '05':
                    return '10';    // Default to IP
                case '06':
                    return '10';    // Default to IP
                case '11':
                    return '10';    // Default to IP
                case '16':
                    return '10';    // Default to IP
                case '65':
                    return '10';    // Default to IP
                case '70':
                    return '8';
                case '86':
                    return '100';   // Default to IP
                case '92':
                    return '7';
            }
            break;

        case 'UPS':
            switch ($service) {
                case 'STD':
                    return '12';
                case '07':
                    return '14';
                case '11':
                    return '12';
                case 'IP':
                case 'IPU':
                    return '11';
                case '65':
                    if ($gateway == 'IGNORE') {
                        return '16'; // UK24
                    } else {
                        return '11'; // IP
                    }
            }
            break;

        default:
            break;
    }

    switch ($service) {
        case 'AIR':
            return '4';
        case 'UK48':
            return '19';
        case 'NI48':
            return '2';
        case 'NI24':
            return '1';
        case 'IE48':
            return '3';
        case 'UK48P':
            return '13';
        case 'RM48':
            return '20';
        case 'TN24':
            return '21';
        case 'TNT':
            return '21';
        case 'STD':
            return '12';
        case 'UK48R':
            return '18';
        case 'UK24':
            return '16';
        case 'UK24S':
            return '16';    // not in services table
        default:
            return 0;
    }
}

function convertCurrencies($fromCurrency, $toCurrency, $value)
{
    $currency = new App\Models\Currency();

    $base = $currency->where('code', strtoupper($fromCurrency))->first();
    $dest = $currency->where('code', strtoupper($toCurrency))->first();
    $newValue = number_format(($value * $dest->rate) / $base->rate, 2);

    return $newValue;
}

function convertLegacyCurrency($currency)
{
    if (strtoupper($currency) == 'UKL') {
        return 'GBP';
    }

    return strtoupper($currency);
}

function convertLegacyTrackingCarrier($depot)
{
    switch (strtoupper($depot)) {
        case 'FXD':
        case 'FXI':
            return 'fedex';

        case 'UPS':
            return 'ups';

        default:
            return 'ifs';
    }
}

function convertLegacyShipDate($createdAt, $recieved, $date, $time)
{
    if ($recieved == 'Y' && $date) {
        return gmtToCarbonUtc($date.' '.$time);
    }

    return gmtToCarbonUtc($createdAt);
}

function convertLegacyCreatedAt($createdAt, $shipDate)
{
    if ($createdAt == '0000-00-00 00:00:00') {
        return gmtToCarbonUtc($shipDate);
    }

    return gmtToCarbonUtc($createdAt);
}

/**
 * Convert legacy gateway to a carrier ID.
 *
 * @param  type  $gateway
 *
 * @return int
 */
function legacyGatewayToCarrierId($gateway)
{
    switch (strtoupper($gateway)) {
        case 'IFSLOC':
        case 'IFSROI':
        case 'IFSEUR':
        case 'AIR':
            return 1;
        case 'FXRS':
        case 'FXD':
            return 2;
        case 'UPS':
            return 3;
        case 'DHL':
            return 5;
        case 'RM':
            return 6;
        case 'PAR':
            return 7;
        case 'TNT':
            return 4;
        case 'IFSUKP':
            return 10;
        default:
            return 1;
    }
}

/**
 * Get manifest id.
 *
 * @param  type  $manifestNumber
 * @param  type  $domestic
 *
 * @return type
 */
function convertLegacyManifestNumber($manifestNumber, $domestic)
{
    if (stristr($manifestNumber, 'AIR')) {
        $manifest = App\Models\Manifest::whereNumber($manifestNumber)->first();
    }

    if ($domestic) {
        $manifest = App\Models\Manifest::whereNumber($manifestNumber)->whereIn(
            'manifest_profile_id',
            [3, 7, 9, 8, 11, 5, 6]
        )->first();
    } else {
        $manifest = App\Models\Manifest::whereNumber($manifestNumber)->whereIn('manifest_profile_id', [1, 2, 12, 4])->first();
    }

    if ($manifest) {
        return $manifest->id;
    }
}

/**
 * Consolidate charges.
 *
 * @param  type  $charges
 *
 * @return string
 */
function consolidateCharges($charges)
{
    foreach ($charges as $charge) {
        $code = $charge['code'];

        if ($code == 'DISC') {
            $code = 'FRT';
        } else {
            $sumCharges[$code]['description'] = $charge['description'];
        }

        if (isset($sumCharges[$code]['value'])) {
            $sumCharges[$code]['value'] += $charge['value'];
        } else {
            $sumCharges[$code]['value'] = $charge['value'];
        }
    }

    $charges = null;

    if (! empty($sumCharges)) {
        foreach ($sumCharges as $code => $charge) {
            $charges[] = ['code' => $code, 'description' => $charge['description'], 'value' => $charge['value']];
        }
    }

    return $charges;
}

/**
 * Sets specific shipment fields as upper/ lowercase as required.
 *
 * @param  type  $shipment
 *
 * @return type
 */
function fixShipmentCase($shipment)
{
    // UPPERCASE (Note: important no spaces in list)
    $fields = 'bill_shipping_account,bill_tax_duty_account,country_of_destination,currency_code,sender_country_code,'
        .'sender_postcode,recipient_postcode,other_country_code,recipient_country_code,other_postcode,'
        .'customs_value_currency_code';
    $changeArray = explode(',', $fields);
    foreach ($changeArray as $field) {
        if (isset($shipment[$field])) {
            $shipment[$field] = trim(strtoupper($shipment[$field]));
        }
    }

    // LOWERCASE (Note: important no spaces in list)
    $fields = 'carrier_code,service_code,bill_shipping,bill_tax_duty,ship_reason,weight_uom,dimension_uom,'
        .'recipient_type,other_type,terms_of_sale,commodity_uom,dims_uom';

    $changeArray = explode(',', $fields);
    foreach ($changeArray as $field) {
        if (isset($shipment[$field])) {
            $shipment[$field] = trim(strtolower($shipment[$field]));
        }
    }

    return $shipment;
}

function calcVolume($length, $width, $height, $divisor = 5000)
{
    if (! is_numeric($divisor)) {
        $divisor = 5000;
    }

    return round(ceil($length) * ceil($width) * ceil($height) / $divisor, 3);
}

function whoPaysDuty($terms)
{
    $terms = strtoupper($terms);
    $whoPaysDuty = [
        'EXW' => 'recipient',
        'FCA' => 'recipient',
        'CPT' => 'recipient',
        'DDP' => 'sender',
        'DAP' => 'recipient',
        'DAT' => 'recipient',
        'CIP' => 'recipient',
    ];

    if (isset($whoPaysDuty[$terms])) {
        return $whoPaysDuty[$terms];
    }
}

function calcDiscPercentage($currentVal, $uploadedVal)
{
    $discount = 0;
    if ($currentVal != 0) {
        $discount = (($currentVal - $uploadedVal) / $currentVal) * 100;
    }

    // Ignore small differences
    if (abs($discount) < .3) {
        $discount = 0;
    }

    return round($discount, 5);
}

/**
 * Log changes to Rates Tables.
 *
 * @param  type  $userId
 * @param  type  $companyId
 * @param  type  $serviceId
 * @param  type  $rateId
 * @param  type  $directory
 * @param  type  $fileName
 * @param  type  $action
 */
function logRateChange($userId, $companyId, $serviceId, $rateId, $directory = '', $fileName = '', $action = '')
{
    return App\Models\RateChangeLogs::create(
        [
            'user_id' => $userId,
            'company_id' => $companyId,
            'service_id' => $serviceId,
            'rate_id' => $rateId,
            'directory' => $directory,
            'filename' => $fileName,
            'action' => $action,
        ]
    );
}

/**
 * @param  type  $sql
 * @param  type  $params
 *
 * @return SQL_String
 */
function rawToSql($sql, $params = [])
{
    $quote = '"';
    foreach ($params as $key => $value) {
        $sql = str_ireplace(":$key", $quote.$value.$quote, $sql);
    }

    return $sql;
}

function girth($length, $width, $height)
{
    // Sort dims so we can identify the longest
    $dims = [$length, $width, $height];
    sort($dims);

    return $dims[2] + ($dims[0] + $dims[1]) * 2;
}

/**
 * Integer to string representation of day.
 *
 * @param  int  $day
 *
 * @return string
 */
function intToDay($day)
{
    if (is_numeric($day) && $day >= 0 && $day <= 6) {
        return jddayofweek($day - 1, 1);
    }

    return 'Unknown';
}

/**
 **************************************************************************
 **************************************************************************
 *                          Unit Test Helpers.
 **************************************************************************
 **************************************************************************
 */

/**
 * ************************************************************************
 * Build/ Allocate user with a specified role/ company.
 * ************************************************************************
 *
 * @param  $userId
 * @param  $roleId
 * @param  $companyId
 *
 * @return null
 * ************************************************************************
 */
function buildTestUser($userId = '', $roleName = '', $companyId = '')
{
    $user = null;

    // If no user defined - fake one
    if (empty($userId)) {
        $user = factory(\App\Models\User::class)->create();
    } else {
        $user = \App\Models\User::findOrFail($userId);
    }

    // Identify supplied role or die with error
    $role = \App\Models\Role::where('name', $roleName)->first();
    if ($role) {
        // Add default company and links
        if ($companyId > '') {
            $user->companies()->detach();
            $user->companies()->attach($companyId);
        }

        // Create Pivots with Parameters
        $user->roles()->detach();
        $user->roles()->attach($role->id);
    } else {
        dd("buildTestUser - Invalid roleName : $roleName when building User");
    }

    return $user;
}
