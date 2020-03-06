<?php

namespace App\Http\Controllers;

use App\Package;
use Illuminate\Http\Request;

class RfController extends Controller
{
    protected $cc;
    protected $routes = ['ADHOC', 'ROAD', 'AIR', 'SEA', 'DHL', 'EUROR', 'FEDC', 'FEDL', 'FEDF', 'NI', 'RM', 'ROI', 'TNT', 'UK1', 'UK2', 'UK24', 'UPS', 'US', 'EXP', 'ALL'];
    protected $route;
    protected $data;

    /**
     * Construct.
     */
    public function __construct()
    {
        $esc = chr(27);
        $this->cc = ['esc' => $esc, 'err' => chr(7).chr(7), 'nl' => $esc.'E', 'dl' => $esc.'E'.$esc.'E', 'cls' => $esc.'[H'.$esc.'[0J'];
    }

    /**
     * Handles incoming data from nodeJs.
     *
     * @param Request $request
     * @return type
     */
    public function server(Request $request)
    {
        $this->session = \App\RfSession::firstOrCreate(['session_id' => $request->id]);
        $this->data = substr(preg_replace('/[^\w]/', '', $request->data), 0, 50);
        $this->route = $this->getRoute();
        $this->scanPackage();
    }

    /**
     * Set the route.
     */
    protected function getRoute()
    {
        if (in_array(strtoupper($this->data), $this->routes)) {
            $this->session->route = strtoupper($this->data);
            $this->session->save();

            return strtoupper($this->data);
        }

        if (! $this->session->route) {
            return $this->getDisplay(null, 'Scan ROUTE', true, true);
        }

        return $this->session->route;
    }

    /**
     * Sets the display.
     *
     * @param mixed (string/array)    $body   Main screen display
     * @param string $prompt Command prompt for user input
     * @param mixed (string/boolean)  $error  Error message display / bell sound
     *
     * @return  string
     */
    protected function getDisplay($body, $prompt = false, $error = false)
    {
        // Clear the screen
        $display = $this->cc['cls'];
        $route = ($this->route) ? $this->route : 'NO ROUTE!';

        // Output centered title
        $display .= $this->center('* RECEIPTS *');
        $display .= $this->cc['dl'];
        $display .= $this->center("== $route ==");
        $display .= $this->cc['dl'];
        $display .= $this->center($body);

        if ($error) {
            // Sound bell
            $display .= $this->cc['err'];
        }

        if ($prompt) {
            $display .= $this->cc['dl'];
            $display .= $prompt.': ';
        }
        echo $display;
        exit;
    }

    /**
     * Center align a string for scanner output.
     *
     * @param type $string
     * @return string
     */
    protected function center($string)
    {
        if (strlen($string) < 30) {
            return str_pad($string, 30, ' ', STR_PAD_BOTH);
        } else {
            return $string;
        }
    }

    /**
     * Scan a package.
     *
     * @return type
     */
    protected function scanPackage()
    {
        // No input
        if (strlen($this->data) == 0 || in_array(strtoupper($this->data), $this->routes)) {
            return $this->getDisplay(null, 'Scan Package');
        }

        $package = Package::whereBarcode($this->data)->first();

        // Package not recognised
        if (! $package) {
            return $this->getDisplay('Package not recognised', 'Scan Package', true);
        }

        // Check route if not bypassed using ALL
        if ($this->route != 'ALL') {
            if ($package->route != $this->route) {
                return $this->getDisplay('Invalid route', 'Scan Package', true);
            }
        }

        // Package already scanned
        if ($package->loaded) {
            return $this->getDisplay('Package already scanned', 'Scan Package', true);
        }

        $datetime = \Carbon\Carbon::now();

        // Package found - update to received
        $package->setReceived($datetime);

        // Packaged loaded to vehicle
        $package->setLoaded($datetime);

        // Shipment on hold, notify operator
        if ($package->shipment->on_hold) {
            return $this->getDisplay('Scanned - SHIPMENT ON HOLD!', 'Scan Package', true);
        }

        $scanCount = $package->shipment->getPackageScanCount();

        $package->shipment->log('Package '.$this->data.' scanned in IFS Antrim Warehouse');

        // Successful scan
        return $this->getDisplay("Pkg $scanCount scanned!", 'Scan Package');
    }
}
