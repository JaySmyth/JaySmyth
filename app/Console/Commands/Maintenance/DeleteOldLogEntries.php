<?php

namespace App\Console\Commands\Maintenance;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldLogEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:delete-old-log-entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old log entries (logs and transaction_logs tables)';

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
        $cutOff = Carbon::now()->subMonths(3)->startOfDay();

        /*
         * Logs (transport jobs) over 3 months
         */

        $this->info('Deleting transport job log entries before '.$cutOff->toDateTimeString());

        \App\Models\Log::where('logable_type', \App\Models\TransportJob::class)
                ->where('created_at', '<=', $cutOff)
                ->delete();

        /*
         * Transaction logs over 3 months
         */

        $this->info('Deleting transaction log entries before '.$cutOff->toDateTimeString());

        \App\Models\TransactionLog::where('created_at', '<=', $cutOff)->delete();

        $this->info('Finished');

        /*
         * Logs over 2 years old
         */

        $cutOff = Carbon::now()->subYears(2)->startOfDay();

        $this->info('Deleting log entries before '.$cutOff->toDateTimeString());

        \App\Models\Log::where('created_at', '<=', $cutOff)->delete();

        /*
         * Truncate the RF sessions table
         */
        DB::table('rf_sessions')->truncate();
    }
}
