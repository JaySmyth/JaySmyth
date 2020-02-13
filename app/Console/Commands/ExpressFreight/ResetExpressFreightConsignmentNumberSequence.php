<?php

namespace App\Console\Commands\ExpressFreight;

use App\Sequence;
use Illuminate\Console\Command;

class ResetExpressFreightConsignmentNumberSequence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:reset-express-freight-consignment-number-sequence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the daily consignment number sequence';

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
        // Load the sequence
        $sequence = Sequence::where('code', 'EXPNICONSIGNMENT')->first();

        // Reset to 0
        $sequence->next_available = 1;
        $sequence->save();
    }
}
