<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateScsJobNumbersOnShipments extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:update-scs-job-numbers-on-shipments {--invoiced=} {--company=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update scs job numbers on shipments';

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
        $invoiced = $this->option('invoiced');
        $company = $this->option('company');

        if (is_numeric($company)) {
            $shipments = \App\Shipment::where('invoicing_status', $invoiced)->whereNull('scs_job_number')->whereNotIn('status_id', [1, 7])->whereNotIn('carrier_id', [6])->whereCompanyId($company)->orderBy('id', 'ASC')->get();
        } else {
            $shipments = \App\Shipment::where('invoicing_status', $invoiced)->whereNull('scs_job_number')->whereNotIn('status_id', [1, 7])->whereNotIn('carrier_id', [6])->where('updated_at', '>=', Carbon::parse('-2 months'))->orderBy('id', 'ASC')->get();
        }

        foreach ($shipments as $shipment) {

            $jobLine = \App\Multifreight\JobLine::select('id', 'job_id')
                    ->orWhere('cargo_desc', 'LIKE', '%AWB:' . $shipment->consignment_number . '%')
                    ->orWhere('cargo_desc', 'LIKE', '%AWB:' . $shipment->carrier_consignment_number . '%')
                    ->first();

            if ($jobLine && $jobLine->scs_job_number) {

                $shipment->scs_job_number = $jobLine->scs_job_number;
                $shipment->invoicing_status = 1;
                $shipment->save();

                $this->info($shipment->consignment_number . ' - FOUND: ' . $jobLine->scs_job_number);
                continue;
            }

            // Check job header for consignment number
            $withHypen = substr($shipment->carrier_consignment_number, 0, 3) . '-' . substr($shipment->carrier_consignment_number, 3);

            $jobHdr = \App\Multifreight\JobHdr::select('job_disp')
                    ->orWhere('hawb_char', $shipment->carrier_consignment_number)
                    ->orWhere('hawb_char', $withHypen)
                    ->orWhere('mawb_char', $shipment->carrier_consignment_number)
                    ->orWhere('mawb_char', $withHypen)
                    ->first();

            if ($jobHdr && $jobHdr->job_disp) {
                $shipment->scs_job_number = $jobHdr->job_disp;
                $shipment->invoicing_status = 1;
                $shipment->save();

                $this->line('Updated using SCS JobHdr');
                continue;
            }

            $this->error("Couldn't find SCS job number for " . $shipment->consignment_number);
        }
    }

}
