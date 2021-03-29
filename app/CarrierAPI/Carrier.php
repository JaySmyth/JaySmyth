<?php

namespace App\CarrierAPI;

use App\CarrierAPI\CWide\CWideAPI;
use App\CarrierAPI\DHL\DHLAPI;
use App\CarrierAPI\DX\DXAPI;
use App\CarrierAPI\ExpressFreight\ExpressFreightAPI;
use App\CarrierAPI\ExpressFreightNI\ExpressFreightNIAPI;
use App\CarrierAPI\Fedex\FedexAPI;
use App\CarrierAPI\IFS\IFSAPI;
use App\CarrierAPI\TNT\TNTAPI;
use App\CarrierAPI\UPS\UPSAPI;
use App\CarrierAPI\XDP\XDPAPI;

/**
 * Build an instance of the required Carrier
 *
 * @author gmcbroom
 */
class Carrier
{
    public function __construct()
    {
    }

    public static function getInstanceOf($instanceType, $mode = 'production')
    {
        switch (strtolower($instanceType)) {
            case 'fedex':
                return new FedexAPI($mode);
                break;

            case 'cwide':
                return new CWideAPI($mode);
                break;

            case 'xdp':
                return new XDPAPI($mode);
                break;

            case 'ups':
                return new UPSAPI($mode);
                break;

            case 'dhl':
                return new DHLAPI($mode);
                break;

            case 'tnt':
                return new TNTAPI($mode);
                break;

            case 'exp':
                return new ExpressFreightAPI($mode);
                break;

            case 'expni':
                return new ExpressFreightNIAPI($mode);
                break;

            case 'ifs':
                return new IFSAPI($mode);
                break;

            case 'dx':
                return new DXAPI($mode);
                break;

            default:
                // Do Nothing
                dd('Unable to build carrier');
                break;
        }
    }
}
