<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessScsCollectionRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:process-scs-collection-requests {--testMode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the multifreight database for new/updated collections and create/update corresponding transport job';

    /**
     * Command running in test mode.
     *
     * @var bool
     */
    protected $testMode = false;

    /**
     * Transport types to ignore.
     *
     * @var array
     */
    protected $ignoreTransportTypes = ['AX', 'CX', 'RX', 'SX'];

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
        if ($this->option('testMode')) {
            $this->line('** TEST MODE **');
            $this->testMode = true;
        }

        // Get viable collections (where dirty flag is set and a header record is present)
        $jobCols = \App\Multifreight\JobCol::whereDirty(1)->orderBy('id', 'ASC')->get();

        $this->info($jobCols->count().' unprocessed records found');

        $today = date('Y-m-d', time());

        foreach ($jobCols as $jobCol) {
            $jobDel = \App\Multifreight\JobDel::where('job_id', $jobCol->job_id)->where('del_no', $jobCol->col_no)->where('del_date', '>=', $today)->first();

            if ($this->isViableJob($jobCol, $jobDel)) {

                // Get array to update the transport jobs table with
                $job = $this->getDataArray($jobCol, $jobDel);

                // Job created/updated successfully - mark as clean
                if ($this->createOrUpdateTransportJob($job)) {
                    if (! $this->testMode) {
                        $jobCol->clean();
                    }
                } else {
                    Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Failed to create transport job for: '.$job->scs_job_number, $job));
                }
            } else {
                // Job not viable or not recent, mark as clean. Leave un-viable jobs from today as dirty as they may be partial records that may come in later.
                if (! $this->testMode) {
                    if ($jobDel && ! empty($jobCol->scs_job_number) || ! $jobCol->created_at->isToday()) {
                        $jobCol->clean();
                    }
                }
            }
        }
    }

    /**
     * Check if a job if viable to raise a request.
     *
     * @param type $jobCol
     * @param type $jobDel
     * @return bool
     */
    protected function isViableJob($jobCol, $jobDel)
    {
        if (! $jobDel || empty($jobCol->scs_job_number)) {
            $this->error('Skipping job: corresponding delivery or header record not found');

            return false;
        }

        $department = \App\Models\Models\Department::whereCode($jobCol->header->job_dept)->first();

        if (! $department) {
            Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Failed to create transport job:'.$jobCol->scs_job_number, 'Department not recognised: '.$jobCol->header->job_dept));
            $this->error('Skipping job: department '.$jobCol->header->job_dept.' not recognised');

            return false;
        }

        // Determine if collection printed
        $colPrinted = $jobCol->printed;
        if ($jobCol->address_code == 0) {
            $colPrinted = 'no';
        }

        // Determine if delivery printed
        $delPrinted = $jobDel->printed;
        if ($jobDel->address_code == 0) {
            $delPrinted = 'no';
        }

        if ($colPrinted == 'no' && $delPrinted == 'no') {
            $this->info('Job not viable: collection or delivery note not printed.');

            return false;
        }

        if (empty($jobCol->name) || empty($jobCol->address1) || empty($jobCol->town)) {
            $this->info('Job not viable: does not contain minimum content.');

            return false;
        }

        if (in_array($jobCol->header->transport_type, $this->ignoreTransportTypes)) {
            $this->info('Job not viable: ignored transport type ('.$jobCol->header->transport_type.').');

            return false;
        }

        // Sea freight exlusions
        if ($department->id == 8) {
            if (in_array($jobCol->name, ['BELFAST CONTAINER TERMINAL', 'COASTAL CONTAINER LINE LTD'])) {
                $this->info('Job not viable: sea freight container.');

                return false;
            }
        }

        return true;
    }

    /**
     * Build an array to create/update the transport jobs table with.
     *
     * @param type $jobCol
     * @param type $jobDel
     *
     * @return array
     */
    protected function getDataArray($jobCol, $jobDel)
    {
        $goodsDescription = $jobCol->header->product_desc;
        $instructions = strip_tags($jobCol->extra_details);

        if (! empty($jobCol->package_type)) {
            $goodsDescription = $goodsDescription.' ('.$jobCol->pieces.' x '.$jobCol->package_type.')';
            $instructions = $instructions.' ('.$jobCol->pieces.' x '.$jobCol->package_type.')';
        }

        return [
            'reference' => $jobCol->scs_job_number,
            'pieces' => $jobCol->pieces,
            'weight' => number_format($jobCol->kgs_wgt, 2, '.', ''),
            'goods_description' => $goodsDescription,
            'volumetric_weight' => number_format($jobCol->entered_cube, 2, '.', ''),
            'instructions' => $instructions,
            'cash_on_delivery' => $jobCol->header->cod_amount,
            'scs_job_number' => $jobCol->scs_job_number,
            'type' => (stristr('IFS Global', $jobDel->name)) ? 'c' : 'd',
            'from_type' => 'c', // (c)ommercial (r)esidential
            'from_name' => $jobCol->contact_name,
            'from_company_name' => $jobCol->name,
            'from_address1' => $jobCol->address1,
            'from_address2' => $jobCol->address2,
            'from_address3' => $jobCol->address3,
            'from_city' => $jobCol->town,
            'from_state' => $jobCol->county,
            'from_postcode' => $jobCol->postcode,
            'from_country_code' => $jobCol->country_code,
            'from_telephone' => $jobCol->telephone,
            'from_email' => $jobCol->email,
            'to_type' => 'c',
            'to_name' => $jobDel->contact_name,
            'to_company_name' => $jobDel->name,
            'to_address1' => $jobDel->address1,
            'to_address2' => $jobDel->address2,
            'to_address3' => $jobDel->address3,
            'to_city' => $jobDel->town,
            'to_state' => $jobDel->county,
            'to_postcode' => $jobDel->postcode,
            'to_country_code' => $jobDel->country_code,
            'to_telephone' => $jobDel->telephone,
            'to_email' => $jobDel->email,
            'visible' => '1',
            'date_requested' => (stristr('IFS Global', $jobDel->name)) ? $jobCol->col_date.' '.$jobCol->col_time : $jobDel->del_date.' '.$jobDel->del_time,
            'department_id' => \App\Models\Models\Department::whereCode($jobCol->header->job_dept)->first()->id,
            'depot_id' => 1,
        ];
    }

    /**
     * Create or update a record in the transport jobs table.
     *
     * @param type $job
     *
     * @return bool
     */
    protected function createOrUpdateTransportJob($job)
    {
        $transportJob = \App\Models\TransportJob::whereReference($job['reference'])->whereType($job['type'])->whereCompleted(0)->first();

        // Convert the datetime sting to a carbon UTC instance
        $job['date_requested'] = gmtToCarbonUtc($job['date_requested']);

        // Existing job
        if ($transportJob) {
            $this->info('Updating transport job '.$transportJob->number.' / '.$job['reference']);

            if (! $this->testMode) {
                $transportJob->update($job);
                $transportJob->setTransendRoute();
            }

            return true;
        }

        $this->info('Create transport job for : '.$job['reference']);

        if (! $this->testMode) {
            $transportJob = \App\Models\TransportJob::create($job);

            // New job created, set the job number and status
            if ($transportJob) {
                $transportJob->number = nextAvailable('JOB');
                $transportJob->setStatus('unmanifested');
                $transportJob->setTransendRoute();

                return true;
            }
        }

        if ($this->testMode) {
            return true;
        }

        return false;
    }
}
