<?php

namespace App\Console\Commands;

use App\Jobs\UploadFedExCommercialInvoice;
use App\Models\Shipment;
use Illuminate\Console\Command;

class UploadFedExCommercialInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:upload-fedex-commercial-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and upload FedEx commercial invoices (ETD)';

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
        foreach (Shipment::whereIn('company_id', [333, 92, 314, 448])->needsFedExEtd()->cursor() as $shipment) {
            $this->info('Dispatching ETD job for shipment '.$shipment->carrier_consignment_number);

            dispatch(new UploadFedExCommercialInvoice($shipment->id));
        }
    }

}
