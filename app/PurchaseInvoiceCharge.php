<?php

namespace App;

use App\CarrierChargeCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceCharge extends Model
{
    /*
     * Black list of NON mass assignable - all others are mass assignable.
     * 
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Charge belongs to an invoice.
     * 
     * @return type
     */
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    /**
     * Charge belongs to an invoice line.
     * 
     * @return type
     */
    public function purchaseInvoiceLine()
    {
        return $this->belongsTo(PurchaseInvoiceLine::class);
    }

    /**
     * Charge belongs to a carrier charge type.
     * 
     * @return type
     */
    public function carrierChargeCode()
    {
        return $this->belongsTo(CarrierChargeCode::class);
    }

    /**
     * Set the carrier charge code id. If charge is not recognised, a charge will be created
     * in the carrier charge codes table. Notification email sent to advise.
     * 
     */
    public function setCarrierChargeId()
    {
        $carrierChargeCode = CarrierChargeCode::whereCarrierId($this->purchaseInvoice->carrier_id)->whereCode($this->code)->first();

        // We don't know about this charge code, add it to the carrier charge codes table
        if (!$carrierChargeCode) {
            $carrierChargeCode = new CarrierChargeCode();
            $carrierChargeCode->code = $this->code;
            $carrierChargeCode->description = ($this->description) ? $this->description : 'Unknown';
            $carrierChargeCode->scs_code = 'MIS';
            $carrierChargeCode->carrier_id = $this->purchaseInvoice->carrier_id;
            $carrierChargeCode->save();

            // Send notification email to let us know that a new charge has been added
            Mail::to('accounts@antrim.ifsgroup.com')->queue(new \App\Mail\UnknownCarrierCharge($this->purchaseInvoice, $carrierChargeCode));
        }

        if ($carrierChargeCode) {
            $this->carrier_charge_code_id = $carrierChargeCode->id;
            $this->save();
        }
    }

    /**
     * Get the SCS code to use for this charge.
     * 
     * @return string
     */
    public function getScsChargeCodeAttribute()
    {
        return $this->carrierChargeCode->scs_code;
    }

    /**
     * Get the amount we were actually billed by the carrier.
     * 
     * @return decimal
     */
    public function getActualBilledAmountAttribute()
    {
        if ($this->billed_amount < 0) {
            return $this->amount - abs($this->billed_amount);
        }

        return $this->billed_amount;
    }

    /**
     * Returns true/false - checks the string against the charge type description
     * 
     * @param type $chargeType
     * @param type $string
     * @return boolean
     */
    function hasDescription($description)
    {
        if (stristr($this->carrierChargeCode->description, $description)) {
            return true;
        }
        return false;
    }

}
