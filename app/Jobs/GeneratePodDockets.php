<?php

namespace App\Jobs;

use App\CarrierAPI\Pdf;
use App\TransportJob;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GeneratePodDockets implements ShouldQueue
{
    use InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * User.
     *
     * @var mixed
     */
    protected $user;
    protected $email = 'transport@antrim.ifsgroup.com';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user = false)
    {
        $this->user = $user;

        if ($this->user) {
            $this->email = $this->user->email;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;

        $transportJobs = TransportJob::whereCompleted(0)->where('status_id', '!=', 7)->where('visible', '1');

        if ($dayOfWeek == 5) {
            $transportJobs = $transportJobs->whereBetween('date_requested', [Carbon::parse('next monday')->startOfDay(), Carbon::parse('next monday')->endOfDay()]);
        } else {
            $transportJobs = $transportJobs->whereBetween('date_requested', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);
        }

        $transportJobs = $transportJobs->orderBy('from_state')
                ->orderBy('from_city')
                ->orderBy('from_postcode')
                ->orderBy('from_company_name')
                ->orderBy('from_name')
                ->orderBy('to_state')
                ->orderBy('to_city')
                ->orderBy('to_postcode')
                ->orderBy('to_company_name')
                ->orderBy('to_name')
                ->get();

        $viable = collect();

        foreach ($transportJobs as $transportJob) :

            // Ignore glen dimplex and cooneen
            if ($transportJob->shipment) {
                if (in_array($transportJob->shipment->company->id, [550, 558])) {
                    continue;
                }
            }

        // Delivery or non courier collection
        if ($transportJob->type == 'd' || ($transportJob->type == 'c' && ! is_numeric($transportJob->shipment_id))) {
            $viable->push($transportJob);
        }

        endforeach;

        // No dockets available
        if ($viable->count() == 0) {
            if ($this->user) {
                Mail::to($this->email)->send(new \App\Mail\GenericError('POD Dockets', 'No jobs currently available - scan freight first'));
            }

            return true;
        }

        $pdf = new Pdf('6X4', 'F');

        foreach ($viable as $transportJob) {
            $pdf->createPodDocket($transportJob, false, false);
        }

        $filePath = storage_path('app/temp/dockets_'.time().'.pdf');

        $pdf->displayPdf($filePath, false);

        Mail::to($this->email)->cc(['transport@antrim.ifsgroup.com', 'it@antrim.ifsgroup.com'])->send(new \App\Mail\GenericError('POD Dockets', 'Please print and distribute to drivers', $filePath));
    }
}
