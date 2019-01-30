<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FukRateExcpt extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'legacy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FUKRateExcpt';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    public function calcCostOoa($postcode)
    {

        // Look for a properly Formated PostCode and if found try it.
        $tmp = explode(' ', $postcode);
        if (count($tmp) == 2) {

            // Seems to be properly formatted, so lets try it.
            $myPostTown = $tmp[0];
            $exception = FukRateExcpt::where('postcode', $myPostTown)->first();

            if ($exception) {
                return $exception->std;
            }
        }

        return null;
    }

    public function calcSalesOOA($companyId, $currency = 'GBP', $postcode)
    {

        $surcharge = NULL;
        $exception = $this->calcCostOoa($postcode);

        if ($exception) {
            
            switch ($currency) {
                case 'GBP':

                    switch ($companyId) {
                        case '201' :
                            // Morrigan Ltd - GHeaney 2010-11-26
                            $surcharge = '14.00';
                            break;

                        case '256' :
                            // Asset Management - GHeaney 2011-12-19
                            $surcharge = '16.00';
                            break;

                        default:
                            $surcharge = '15.00';
                            break;
                    }
                    break;

                case 'EUR':
                    $surcharge = '18.50';
                    break;

                default:
                    $surcharge = 0;
                    break;
            }
        }

        return $surcharge;
    }

}
