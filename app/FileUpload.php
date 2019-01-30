<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FileUpload extends Model
{
    /*
     * Mass assignable.
     */

    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_upload', 'next_upload'];

    /**
     * A file upload is owned by a company.
     *
     * @return
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A file upload is owned by an upload host.
     *
     * @return
     */
    public function fileUploadHost()
    {
        return $this->belongsTo(FileUploadHost::class);
    }

    /**
     * A file upload has many logs.
     *
     * @return
     */
    public function fileUploadLog()
    {
        return $this->hasMany(FileUploadLog::class)->orderBy('id', 'desc');
    }

    /**
     * Returns last 20 log entries.
     * 
     * @return type
     */
    public function getLatestLogs()
    {
        return $this->fileUploadLog()->limit(20)->get();
    }

    /**
     * Determine if a file upload is scheduled to run.
     * 
     * @return boolean
     */
    public function isScheduled()
    {
        if (!$this->enabled) {
            return false;
        }

        // Next upload within 2 minutes or never ran before
        if (Carbon::now()->diffInMinutes($this->next_upload) <= 2 || (!$this->last_upload && !$this->next_upload)) {
            return true;
        }

        return false;
    }

    /**
     * Schedule an upload to run again.
     *
     * @param integer $minutes
     */
    public function retry($minutes = 2)
    {
        $this->next_upload = Carbon::now()->addMinutes($minutes);
        $this->update();
    }

    /**
     * Sets the next upload time according to upload frequency.
     */
    public function setNextUpload()
    {
        switch ($this->frequency) {
            case 'hourly':
                $today = Carbon::today();
                $nextHour = Carbon::now()->hour + 1;

                $this->next_upload = $today->addHours($nextHour);
                break;

            case 'daily':
                $this->next_upload = new Carbon('tomorrow ' . $this->time);
                break;

            case 'twiceDaily':
                $times = explode(',', $this->time);

                $time1 = new Carbon('today ' . $times[0]);
                $time2 = new Carbon('today ' . $times[1]);

                if (Carbon::now()->hour == $time2->hour) {
                    $this->next_upload = new Carbon('tomorrow ' . $times[0]);
                } else {
                    $this->next_upload = new Carbon('today ' . $times[1]);
                }
                break;

            case 'weekly':
                $nextUpload = new Carbon('today ' . $this->time);
                $this->next_upload = $nextUpload->addWeek();
                break;

            case 'monthly':
                $nextUpload = new Carbon('today ' . $this->time);
                $this->next_upload = $nextUpload->addMonth();
                break;

            default:
                $this->next_upload = new Carbon('tomorrow ' . $this->time);
                break;
        }

        $this->update();
    }

}
