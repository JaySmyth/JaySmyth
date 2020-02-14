<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartRfserver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:start-rfserver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the RF Server';

    /*
     * Path to nodejs - loaded from .env
     *
     * @var string
     */
    protected $pathToNode;

    /*
     * Server javacript filename - loaded from .env
     *
     * @var string
     */
    protected $serverName;

    /*
     * Hostname of the server - calculated from app url
     *
     * @var string
     */
    protected $host;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->pathToNode = config('services.rfserver.path_to_node');
        $this->serverName = config('services.rfserver.name');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->serverIsRunning()) {
            exit;
        }

        exec("cd /var/www/node_telnet;$this->pathToNode $this->serverName&");
    }

    /**
     * Check if the rf server is running.
     *
     * @return bool
     */
    private function serverIsRunning()
    {
        exec('ps aux | grep "'.$this->serverName.'"', $output);

        foreach ($output as $value) {
            if (stristr($value, $this->pathToNode.' '.$this->serverName)) {
                $this->info('RF Server running');

                return true;
            }
        }

        return false;
    }
}
