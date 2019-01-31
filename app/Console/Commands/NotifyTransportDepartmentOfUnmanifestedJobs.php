<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\TransportJob;

class NotifyTransportDepartmentOfUnmanifestedJobs extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:notify-transport-department-of-unmanifested-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Advise transport department that there are jobs that need to be manifested';

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
        $transportJob = new TransportJob();

        $transportJobs = $transportJob->unmanifested();

        if ($transportJobs->count() > 25) {
            Mail::to('transport@antrim.ifsgroup.com')->send(new \App\Mail\UnmanifestedTransportJobs($transportJobs));
        }
    }

}
