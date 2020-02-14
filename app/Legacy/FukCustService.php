<?php

namespace App\Legacy;

use Illuminate\Database\Eloquent\Model;

class FukCustService extends Model
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
    protected $table = 'FUKCustServices';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * Return Services for this customer.
     *
     * @param type $company
     * @return type
     */
    public function getCompanyServices($company)
    {
        $services = $this->where('company', $company->company)
                ->where('app', 'courierUK')
                ->where('IFSDepot', $company->IFSDepot)
                ->get();

        if ($services->isEmpty()) {
            $services = $this->where('company', 'DEF')
                    ->where('app', 'courierUK')
                    ->where('IFSDepot', $company->IFSDepot)
                    ->get();
        }

        $intlServices = $this->where('company', $company->company)
                ->where('app', 'courierIntl')
                ->where('IFSDepot', $company->IFSDepot)
                ->get();

        if ($intlServices->isEmpty()) {
            $intlServices = $this->where('company', 'DEF')
                    ->where('app', 'courierIntl')
                    ->where('IFSDepot', $company->IFSDepot)
                    ->get();
        }

        return $services->merge($intlServices);
    }

    public function getCarrierId()
    {
        switch (strtoupper($this->gateway)) {

            case 'UPS':
                return 3;
                break;

            case 'FXD':
            case 'FXRS':
                return 2;
                break;

            case 'DHL':
                return 5;
                break;

            case 'TNT':
                return 4;
                break;

            case 'PAR':
                return 7;
                break;

            case 'RM':
                return 6;
                break;

            case 'IFSUKP':
            case 'IFSLOC':
            case 'IFSROI':
                return 1;
                break;

            default:
                return;
                break;
        }
    }
}
