<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IfsNdPostcode extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Postcode served by IFS.
     *
     * @param $postcode
     * @return bool
     */
    public function isServed($postcode)
    {

        // Look to see if postcode is "Not Served"
        for ($i = strlen($postcode); $i >= 2; $i--) {
            $notServed = $this->where('postcode', '=', substr($postcode, 0, $i))->first();

            if ($notServed) {
                return false;
            }
        }

        return true;
    }
}
