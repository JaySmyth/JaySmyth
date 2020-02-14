<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePodDockets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:generate-pod-dockets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email POD dockets to transport (jobs for the next day)';

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
        dispatch(new \App\Jobs\GeneratePodDockets());
    }
}
