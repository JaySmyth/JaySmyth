<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckTaskSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-task-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test email at 9am / 3pm';

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
        Mail::to('it@antrim.ifsgroup.com')->send(new \App\Mail\GenericError('Check task schedule - ran at 9:02am / 3pm', 'Server date: ' . date('d-m-Y H:i:s', time())));
    }
}
