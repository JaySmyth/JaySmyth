<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckRfserver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-rfserver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the RF Server';

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
        try {
            $fp = fsockopen(config('app.host'), 23, $errno, $errstr, 10);

            if (! $fp) {
                $this->error("RF Server not running: $errstr ($errno)");
                $this->sendMail($errstr);
                exit;
            }

            // Send a "ctrl-D" - node server termiates the session on ctrl-D
            fwrite($fp, chr(4));

            // Get the output
            while (! feof($fp)) {
                fgets($fp, 128);
            }

            // Close the connection
            fclose($fp);

            $this->info('RF Server running');
        } catch (\ErrorException $ex) {
            $this->error('RF Server not running: '.$ex->getMessage());
            $this->sendMail($ex->getMessage());
        }
    }

    /**
     * Send email to notify of issue.
     *
     * @param type $error
     */
    private function sendMail($error)
    {
        mail(config('app.mail'), 'RF Server DOWN ('.config('app.url').')', "The RF Server is not currently running. It will be checked in another 5 minutes time.\n\nIf you do not receive another of these emails, the server is running successfully.\n\n**$error**");
    }
}
