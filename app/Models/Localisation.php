<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    public $timestamps = false;

    /**
     * Get PHP date format.
     *
     * @return string
     */
    public function getPhpDateFormatAttribute()
    {
        switch ($this->date_format) {
            case 'yyyy-mm-dd':
            case 'yyyy/mm/dd':
                return 'Y-m-d';
            case 'dd-mm-yyyy':
            case 'dd/mm/yyyy':
                return 'd-m-Y';
            case 'mm-dd-yyyy':
            case 'mm/dd/yyyy':
                return 'm-d-Y';
            default:
                return 'd-m-Y';
        }
    }

    /**
     * @return string
     */
    public function getVerbosePhpDateFormatAttribute()
    {
        switch ($this->date_format) {
            case 'mm-dd-yyyy':
            case 'mm/dd/yyyy':
                return 'M jS - Y';

            default:
                return 'jS M - Y';
        }
    }
}
