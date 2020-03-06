<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sequence extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass assign.
     *
     * @var array
     */
    protected $fillable = ['next_available'];

    /*
     * to use - Sequence::whereCode('CONSIGNMENT')->lockForUpdate()->first()->getNextAvailable();
     */

    /**
     * Calculates next number in sequence
     * Adding any check digits etc. required.
     *
     * @return bool
     */
    public function getNextAvailable()
    {
        $number = $this->next_available;
        $this->increment('next_available');

        switch ($this->code) {

            case 'CONSIGNMENT':
                // Add Modulus 10 checkdigit
                return $number.mod10CheckDigit($number);

            // Transport Job
            case 'JOB':
                // Add Modulus 10 checkdigit
                return 'TP'.$number.mod10CheckDigit($number);

            // Driver Manifest
            case 'DRIVER':
                // Add Modulus 10 checkdigit
                return 'DM'.$number.mod10CheckDigit($number);

            case 'DHLMAIL':
                // Add Modulus 11 checkdigit
                return $number.mod11CheckDigit($number);

            default:
                // Return unchanged
                return $number;
        }
    }
}
