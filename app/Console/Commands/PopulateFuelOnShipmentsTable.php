<?php

namespace App\Console\Commands;

use App\Shipment;
use Illuminate\Console\Command;

class PopulateFuelOnShipmentsTable extends Command
{

    public $quoted;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:populate-fuel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates fuel_cost & fuel_charge fields on shipments table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Shipment::where("quoted", "!=", "0")->whereNull('fuel_charge')->chunk(100000, function ($shipments) {

            foreach ($shipments as $shipment) {
                echo "Shipment : ".$shipment->id."\n";
                $this->quoted = json_decode($shipment->quoted, true);
                if (is_array($this->quoted)) {
                    $shipment->fuel_cost = $this->sumCharges($this->quoted['costs'], 'FUEL');
                    $shipment->fuel_charge = $this->sumCharges($this->quoted['sales'], 'FUEL');
                    $shipment->save();
                    echo " ->";
                }
                echo "\n";
            }
        });

        Shipment::where("quoted", "!=", "0")->where('fuel_charge','0')->chunk(100000, function ($shipments) {

            foreach ($shipments as $shipment) {
                echo "Shipment : ".$shipment->id."\n";
                $this->quoted = json_decode($shipment->quoted, true);
                if (is_array($this->quoted)) {
                    $shipment->fuel_cost = $this->sumCharges($this->quoted['costs'], 'FUEL');
                    $shipment->fuel_charge = $this->sumCharges($this->quoted['sales'], 'FUEL');
                    $shipment->save();
                    echo " ->";
                }
                echo "\n";
            }
        });

    }

    public function sumCharges($charges, $chargeType = 'FUEL')
    {
        $fuel = 0;
        foreach ($charges as $charge) {
            echo "Code :".$charge['code']." Charge :".$charge['value']."\n";
            if ($charge['code'] == $chargeType && is_numeric($charge['value'])) {
                $fuel += $charge['value'];
            }
        }
        return $fuel;
    }
}
