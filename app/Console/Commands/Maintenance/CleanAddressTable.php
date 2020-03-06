<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;

class CleanAddressTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:clean-address-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete duplicate addresses and incomplete records';

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
        $total = 0;

        $addresses = \App\Models\Models\Address::whereNotNull('id')->groupBy('company_id', 'name', 'company_name', 'address1')->havingRaw('count(*) > 1')->get();

        $this->info($addresses->count().' duplicate addresses found');

        foreach ($addresses as $duplicate) {
            $duplicateAddress = \App\Models\Models\Address::whereCompanyId($duplicate->company_id)->whereName($duplicate->name)->whereCompanyName($duplicate->company_name)->whereAddress1($duplicate->address1)->orderBy('id', 'ASC')->get();

            $this->line($duplicateAddress->count().' duplicate addresses loaded');

            foreach ($duplicateAddress as $add) {
                $count = \App\Models\Models\Address::whereCompanyId($duplicate->company_id)->whereName($duplicate->name)->whereCompanyName($duplicate->company_name)->whereAddress1($duplicate->address1)->count();

                if ($count > 1) {
                    $this->error('Deleting duplicate ('.$add->id.'): '.$add->name.' - '.$add->address1);
                    $add->delete();
                    $total++;
                } else {
                    $this->info('No more duplicates found for: '.$add->name.' - '.$add->address1);
                }
            }
        }

        $this->line('Deleting records associated with redundant companies');

        $companies = \App\Models\Models\Company::whereDepotId(4)->get();

        foreach ($companies as $company) {
            $deleted = \App\Models\Models\Address::whereCompanyId($company->id)->delete();

            if ($deleted > 0) {
                $this->error($company->company_name.' (redundant): '.$deleted.' addresses deleted');
                $total += $deleted;
            }
        }

        $this->info("FINISHED: $total addresses deleted!");
    }
}
