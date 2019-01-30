<?php

namespace Tests\Unit;

use TestCase;
use App\User;
use App\Company;
use App\Shipment;
use App\Pricing\Pricing;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RePricingTest extends TestCase {

    private $user;
    private $companies;
    private $shipments;
    private $checkDate;

    /**
     * Initial Setup to run Test as a named User
     * and initialise the Pricing Object
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = User::find(85);     // G McBroom
        $this->actingAs($this->user);

        $this->checkDate = \Carbon\Carbon::today()->modify("last weekday")->format('Y-m-d');     // Last working day
        $this->companies = Company::where('legacy', 0)->pluck('id')->toArray();                 // Get all Non Legacy Customers
        $this->shipments = Shipment::whereIn('company_id', $this->companies)
                ->whereDate('collection_date', $this->checkDate)
                ->whereNotNull('quoted')
                ->get();

        $this->pricing = new Pricing();
    }

    /**
     * Accepts Priced Shipment and compares with
     * what we expect, raising errors if appropriate
     * 
     * @param type $target
     * @param type $prices
     */
    public function checkMatch($quoted, $prices, $text = '')
    {

        // Save data so that it is available to the custom error fn
        $this->quoted = $quoted;
        $this->prices = $prices;

        $display = "\n" . $text . " " . $this->buildError($this->quoted, $this->prices);

        $this->assertEquals($this->quoted['shipping_cost'], $this->prices['shipping_cost'], $display . ' - Costs do not match', 0.005);
        $this->assertEquals($this->quoted['shipping_charge'], $this->prices['shipping_charge'], $display . ' - Sales do not match', 0.005);
    }

    public function buildError($quoted, $prices)
    {

        $error = "\n    Original - Cost : " . $quoted['shipping_cost'] . " Sales : " . $quoted['shipping_charge'] . " ";
        $error .= "\n    Repriced - Cost : " . $prices['shipping_cost'] . " Sales : " . $prices['shipping_charge'];

        return $error;
    }

    public function displayValues($prices, $heading)
    {

        $error = "";
        foreach ($prices as $charge) {
            switch (strtoupper($charge['code'])) {

                case 'FUEL':
                    // Do nothing
                    break;

                default:
                    $error .= " $heading : " . $charge['code'] . " Value : " . $charge['value'];
                    break;
            }
        }

        return $error;
    }

    /*
     * ************************************
     * ***     Start of Unit Tests      ***
     * ************************************
     * 
     * Note:
     *      Target values should exclude
     *      Fuel Surcharge.
     */

    public function testHeading()
    {

        echo "\n******************************************";
        echo "\n               Unit Test";
        echo "\n Check Shipments for non legacy customers";
        echo "\n   With a collection date of " . $this->checkDate;
        echo "\n  Comparing price against original quote";
        echo "\n******************************************\n";
        $this->assertEquals(1, 1);
    }

    /*
     * ************************************
     *             Carrier IFS
     * ************************************
     */

    public function test_reprice_last_working_days_shipments()
    {

        echo "\n" . count($this->shipments) . " Shipments to be repriced\n";

        switch (count($this->shipments)) {

            case "0":

                echo "\rNo Shipments Selected\n";
                break;

            case "1":

                $quoted = json_decode($this->shipments->quoted, true);
                $prices = $this->shipments->price(false);
                $this->checkMatch($quoted, $prices, 'Repricing ' . $this->shipments->consignment_number);
                break;

            default:

                foreach ($this->shipments as $shipment) {

                    $quoted = json_decode($shipment->quoted, true);
                    $prices = $shipment->price(false);
                    $this->checkMatch($quoted, $prices, 'Repricing ' . $shipment->consignment_number);
                }
                break;
        }

        echo "\n" . count($this->shipments) . " Shipments repriced\n";
    }

}
