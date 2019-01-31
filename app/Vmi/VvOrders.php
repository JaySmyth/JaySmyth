<?php

namespace App\Vmi;

use Illuminate\Database\Eloquent\Model;

class VvOrders extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'vmi';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vv_orders';

    /*
     * Not mass assignable
     */
    protected $guarded = ['id'];

    /*
     * No timestamps.
     */
    public $timestamps = false;

    /**
     * Get the associated courier company.
     * 
     * @return type
     */
    public function getCompany()
    {
        $vvCompany = \App\Vmi\Company::find($this->buyer_id);

        if ($vvCompany && is_numeric($vvCompany->laravel_company_id)) {
            return \App\Company::find($vvCompany->laravel_company_id);
        }

        return false;
    }

    /**
     * 
     * @return type
     */
    public function stockItems()
    {
        return $this->hasMany(VvOrdersStock::class, 'order_id', 'id')->orderBy('id', 'ASC');
    }

    /**
     * Update the shipment dispatch details.
     */
    public function setToDispatched($result)
    {
        $this->dispatch_carrier = $result['carrier'];
        $this->dispatch_tracking_number = $result['ifs_consignment_number'];
        $this->dispatch_comments = 'Dispatched via ' . $result['carrier'] . ' - ' . $result['consignment_number'];
        $this->courier_dispatch_status = 2;
        $this->save();
    }

}
