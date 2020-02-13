<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckJobQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifs:check-job-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the number of Jobs in Queue is < maxCount';

    /**
     * The Maximum number of emails to send.
     *
     * @var string
     */
    protected $maxEmails = 5;

    /**
     * Number of jobs in the Queue.
     *
     * @var string
     */
    protected $queueSize = 0;

    /**
     * Max number of jobs we would expect to see in the Queue.
     *
     * @var string
     */
    protected $maxQueueSize = 200;

    /**
     * Email address to send error to.
     *
     * @var string
     */
    protected $contactEmail = 'it@antrim.ifsgroup.com';

    /**
     * Number of times an alarm has been raised
     * Starts at zero so first alarm does not
     * send an email.
     *
     * @var string
     */
    protected $loopCount = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (Storage::exists('temp/jobErrorCount.txt')) {
            $this->loopCount = intval(Storage::get('temp/jobErrorCount.txt'));
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->queueSize = DB::table('jobs')->count();
        if ($this->queueSize > $this->maxQueueSize) {

            // Too many jobs in the queue
            $this->raiseAlarm();
            $this->loopCount++;
            Storage::put('temp/jobErrorCount.txt', $this->loopCount);
        } else {

            // Everything back to normal - Clear up if necessary
            if (Storage::exists('temp/jobErrorCount.txt') && $this->loopCount > 1) {
                $message = "Job Queue now back within normal parameters - $this->queueSize Jobs - Max : $this->maxQueueSize jobs.";
                mail($this->contactEmail, 'AWS Job Queue Restored', $message);
            }
            Storage::delete('temp/jobErrorCount.txt');
        }
    }

    /**
     * Raise the alarm by sending emails when appropriate.
     */
    public function raiseAlarm()
    {

        /*
         * **************************************
         * If too many jobs, then raise the alarm
         * **************************************
         */
        // Don't send email for first time. Give system a chance to process
        if ($this->loopCount >= 1) {

            // Send email unless max emails have been sent
            if ($this->loopCount <= $this->maxEmails) {
                $this->createEmail('email');
            } else {

                // Send a reminder every 2 hours
                if ($this->loopCount % 24 === 0) {
                    $this->createEmail('reminder');
                }
            }
        }
    }

    public function createEmail($emailType)
    {

        // Set email subject
        $subject = "Possible AWS Job Queue Failure - $this->queueSize Jobs - ";
        if ($emailType == 'reminder') {
            $subject = "Reminder - $subject";
        }

        if ($this->loopCount <= $this->maxEmails) {
            $subject .= 'Msg '.$this->loopCount;
        }

        // Build Message text
        $message = "Warning the AWS Job Queue has more than $this->maxQueueSize Jobs.\nPlease investigate.\n\n";
        $message .= "If required, the following command may be used to Re-Start the Queue and Queue Supervisor\n\n";
        $message .= "    restart_job_queue\n\nThis attempts to run the following commands :\n\n";
        $message .= "    php artisan queue:restart\n    php artisan queue:retry all\n    service supervisor restart\n\n";

        // If the 5th time or greater then attempt to restart the Queue
        if ($this->loopCount >= $this->maxEmails) {
            $message .= "******************************************************************\n";
            $message .= "  The system will now attempt to automatically restart the Queue\n";
            $message .= "******************************************************************\n\n";
            $message .= "If successful, the number of jobs in the jobs table should start to reduce.\n";
            $message .= "If not, then manual intervention will be required.\n\n";
            $message .= "Trying now...\n\n";

            // Execute bash script to restart job queue
            $resp = exec('restart-job-queue');
            $message .= "Response received:\n\n";

            if (is_array($resp)) {
                foreach ($resp as $line) {
                    $message .= $line."\n";
                }
            }
        }

        mail($this->contactEmail, $subject, $message);
    }
}
