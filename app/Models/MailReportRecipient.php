<?php

 namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MailReportRecipient extends Model
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
    protected $dates = ['last_run', 'next_run'];

    /**
     * @param type $value
     * @return type
     */
    public function getToArrayAttribute()
    {
        if (stristr($this->to, ';')) {
            return explode(';', $this->to);
        }

        return $this->to;
    }

    /**
     * @param type $value
     * @return type
     */
    public function getBccArrayAttribute()
    {
        if (stristr($this->bcc, ';')) {
            return explode(';', $this->bcc);
        }

        return $this->bcc;
    }

    /**
     * Get the decoded criteria as array.
     *
     * @return string
     */
    public function getCriteriaArrayAttribute()
    {
        return json_decode($this->criteria, true);
    }

    /**
     * Determine if the report should be run and set the next run time.
     */
    public function isRunable()
    {
        // enabled, next_run within 2 minutes or never ran before
        if ($this->enabled && (Carbon::now()->diffInMinutes($this->next_run) <= 2 || (! $this->last_run && ! $this->next_run))) {
            switch ($this->frequency) {
                case 'hourly':
                    $nextRun = new Carbon('today 00:'.$this->time);
                    $this->next_run = $nextRun->addHours(Carbon::now()->hour + 1);
                    break;

                case 'daily':
                    $this->next_run = new Carbon('tomorrow '.$this->time);
                    break;

                case 'twiceDaily':
                    $times = explode(',', $this->time);

                    $time1 = new Carbon('today '.$times[0]);
                    $time2 = new Carbon('today '.$times[1]);

                    if (Carbon::now()->hour == $time2->hour) {
                        $this->next_run = new Carbon('tomorrow '.$times[0]);
                    } else {
                        $this->next_run = new Carbon('today '.$times[1]);
                    }
                    break;

                case 'weekly':
                    $nextRun = new Carbon('today '.$this->time);
                    $this->next_run = $nextRun->addWeek();
                    break;

                case 'monthly':
                    $nextRun = new Carbon('today '.$this->time);
                    $this->next_run = $nextRun->addMonth();
                    break;

                default:
                    $this->next_run = null;
                    break;
            }

            $this->last_run = Carbon::now();
            $this->save();

            return true;
        }

        return false;
    }
}
