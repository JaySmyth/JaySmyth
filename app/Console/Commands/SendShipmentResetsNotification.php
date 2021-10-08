<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendShipmentResetsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:send-shipment-resets-notification {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email notifying of shipment resets';

    /**
     * The default recipient.
     *
     * @var string
     */
    protected $recipients = ['aplatt@antrim.ifsgroup.com', 'courieruk@antrim.ifsgroup.com', 'markj@antrim.ifsgroup.com'];

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
        $shipments = Shipment::orderBy('id')
            ->shipDateBetween(now()->modify('last weekday')->startOfDay(), now()->modify('last weekday')->endOfDay())
            ->whereNotIn('status_id', [1, 7])
            ->whereReset(true)
            ->with('service')
            ->get();

        if ($this->option('test')) {
            $this->recipients = ['dshannon@antrim.ifsgroup.com'];
        }

        if ($shipments->count() > 0) {
            Mail::to($this->recipients)->send(new \App\Mail\ShipmentResets($shipments, 'Shipment resets - '.now()->modify('last weekday')->format('d-m-y')));
        }
    }

}
