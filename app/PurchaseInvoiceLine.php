<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shipment;
use Carbon\Carbon;

class PurchaseInvoiceLine extends Model
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
    protected $dates = ['ship_date', 'delivery_date', 'created_at', 'updated_at'];

    /**
     * Purchase invoice line belongs to an invoice.
     *
     * @return type
     */
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    /**
     * Purchase invoice line belongs to a shipment.
     *
     * @return type
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class)->select('id', 'company_id', 'carrier_tracking_number', 'scs_job_number')->with(['company' => function ($query) {
                        $query->select('id', 'company_name');
                    }]);
    }

    /**
     * A purchase invoice line has many charges
     *
     * @return
     */
    public function charges()
    {
        return $this->hasMany(PurchaseInvoiceCharge::class)->with('carrierChargeCode');
    }

    /**
     * A purchase invoice line has one SCS job.
     *
     * @return type
     */
    public function scsJob()
    {
        return $this->hasOne(Legacy\MfJobHdr::class, 'job_disp', 'scs_job_number')->select('job_id', 'job_disp')->with('costs');
        //return $this->hasOne(Multifreight\JobHdr::class, 'job_disp', 'scs_job_number')->select('job_id', 'job_disp')->with('costs');
    }

    /**
     * Passed by user.
     *
     * @return type
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ************************************************************************************************************************* //
    // **************************************************** IFS TOTALS ********************************************************* //
    // ************************************************************************************************************************* //

    /**
     * Get the total of ALL costs IFS has allowed for this line.
     */
    public function getTotalIfsAttribute()
    {
        $total = 0;

        if (!$this->scsJob) {
            return $total;
        }

        $ignore = [];

        // Do not add CDV and ADM charges if this is a freight invoice (except DHL - freight and duty combined in same invoice)
        if ($this->purchaseInvoice->type == 'F' && $this->purchaseInvoice->carrier_id != 5) {
            array_push($ignore, 'CDV', 'ADM');
        }

        foreach ($this->scsJob->costs as $cost) {
            if (!in_array($cost->charge_type, $ignore)) {
                $total += $cost->cost_rate;
            }
        }
        return round($total, 2);
    }

    /**
     * Get the total FREIGHT charges IFS have allowed for this line.
     */
    public function getTotalFreightIfsAttribute()
    {
        if (!$this->scsJob) {
            return 0;
        }

        return round($this->scsJob->costs->where('charge_type', '=', 'FRT')->sum('cost_rate'), 2);
    }

    /**
     * Get the total fuel surcharge IFS have allowed for this line.
     */
    public function getTotalFuelSurchargeIfsAttribute()
    {
        if (!$this->scsJob) {
            return 0;
        }

        return round($this->scsJob->costs->where('charge_type', '=', 'FSC')->sum('cost_rate'), 2);
    }

    /**
     * Get the total of all Miscellaneous charge types that IFS have allowed for this line.
     */
    public function getTotalMiscellaneousIfsAttribute()
    {
        if (!$this->scsJob) {
            return 0;
        }

        return round($this->scsJob->costs->where('charge_type', '=', 'MIS')->sum('cost_rate'), 2);
    }

    /**
     * Get the total of all other charge types that IFS have allowed for this line.
     */
    public function getTotalOtherChargesIfsAttribute()
    {
        $total = 0;

        if (!$this->scsJob) {
            return $total;
        }

        $ignore = ['FRT', 'FSC'];

        // Do not add CDV and ADM charges if this is a freight invoice (except DHL - freight and duty combined in same invoice)
        if ($this->purchaseInvoice->type == 'F' && $this->purchaseInvoice->carrier_id != 5) {
            array_push($ignore, 'CDV', 'ADM');
        }

        foreach ($this->scsJob->costs as $cost) {
            if (!in_array($cost->charge_type, $ignore)) {
                $total += $cost->cost_rate;
            }
        }
        return round($total, 2);
    }

    // ************************************************************************************************************************* //
    // ************************************************** CARRIER TOTALS ******************************************************* //
    // ************************************************************************************************************************* //

    /**
     * Get the total of all charges from the CARRIER (excluding VAT).
     *
     * @return string
     */
    public function getTotalAttribute()
    {
        return round($this->charges->whereNotIn('code', [173, 'CDV'])->sum('billed_amount'), 2);
    }

    /**
     * Get the total FREIGHT charge from the CARRIER (sum of FRT - discounts)
     *
     */
    public function getTotalFreightAttribute()
    {
        $frt = $this->charges->where('scs_charge_code', '=', 'FRT')->sum('billed_amount');

        return round($frt - $this->total_discount, 2);
    }

    /**
     * Get the total FUEL SURCHARGE from the CARRIER
     *
     */
    public function getTotalFuelSurchargeAttribute()
    {
        return round($this->charges->where('scs_charge_code', '=', 'FSC')->sum('billed_amount'), 2);
    }

    /**
     * Get the total MISCELLANEOUS charges from the CARRIER
     *
     */
    public function getTotalMiscellaneousAttribute()
    {
        return round($this->charges->where('scs_charge_code', '=', 'MIS')->sum('billed_amount'), 2);
    }

    /**
     * Get the total of ALL OTHER charges from the CARRIER (all charges excluding FRT and FSC)
     *
     */
    public function getTotalOtherChargesAttribute()
    {
        $total = 0;
        foreach ($this->charges as $charge) {
            if (!in_array($charge->scs_charge_code, ['FRT', 'FSC']) && !$charge->hasDescription('DISCOUNT') && !$charge->hasDescription('VAT')) {
                $total += $charge->billed_amount;
            }
        }
        return round($total, 2);
    }

    /**
     * Get the total VAT from the CARRIER.
     *
     * @return string
     */
    public function getTotalVatAttribute()
    {
        $total = 0;

        if ($this->purchaseInvoice->type == 'D') {
            return $total;
        }

        foreach ($this->charges as $charge) {
            if ($charge->hasDescription('VAT') || $charge->vat_applied) {
                if ($charge->vat_applied) {
                    $total += $charge->vat;
                } else {
                    $total += $charge->billed_amount;
                }
            }
        }
        return round($total, 2);
    }

    /**
     * Get the discount total.
     *
     * @return type
     */
    public function getTotalDiscountAttribute()
    {
        $total = 0;
        foreach ($this->charges as $charge) {
            if ($charge->hasDescription('DISCOUNT')) {
                $total += abs($charge->billed_amount);
            }
        }
        return round($total, 2);
    }

    // ************************************************************************************************************************* //
    // **************************************************** OVERCHARGE FLAGS *************************************************** //
    // ************************************************************************************************************************* //

    /**
     * True/false if carrier has overcharged somewhere.
     *
     * @return mixed
     */
    public function getOverchargeAttribute()
    {
        if ($this->freight_overcharge || $this->fuel_surcharge_overcharge || $this->other_charges_overcharge || $this->total > $this->total_ifs) {
            return true;
        }

        return false;
    }

    /**
     * True/false if carrier has charged more FRT than IFS have allowed.
     *
     * @return boolean
     */
    public function getFreightOverchargeAttribute()
    {
        if ($this->total_freight > $this->total_freight_ifs) {
            return true;
        }
        return false;
    }

    /**
     * True/false if carrier has charged more FSC than IFS have allowed.
     *
     * @return boolean
     */
    public function getFuelSurchargeOverchargeAttribute()
    {
        /*
         * Import jobs or UPS jobs - FSC should be the same or less than what IFS have allowed
         */
        if (stristr($this->scs_job_number, 'IFCIM') || $this->purchaseInvoice->carrier->code == 'ups') {
            if ($this->total_fuel_surcharge > $this->total_fuel_surcharge_ifs) {
                return true;
            }

            return false;
        }

        /*
         * FedEx jobs - fuel surcharge should be 50% or less of the IFS Fuel surcharge
         */
        if ($this->purchaseInvoice->carrier->code == 'fedex') {
            $acceptedFscLevel = $this->total_fuel_surcharge_ifs / 2;
            $acceptedFscLevel += 0.01; // Add tolerance

            if ($this->total_fuel_surcharge > $acceptedFscLevel) {
                return true;
            }

            return false;
        }

        /*
         * Default.
         */
        if ($this->total_fuel_surcharge > $this->total_fuel_surcharge_ifs) {
            return true;
        }

        return false;
    }

    /**
     * True/false if carrier has charged more "other" than IFS have allowed.
     *
     * @return boolean
     */
    public function getOtherChargesOverchargeAttribute()
    {
        if ($this->total_other_charges > $this->total_other_charges_ifs) {
            return true;
        }
        return false;
    }

    /**
     * Determine if there has been an overcharge and who by.
     *
     * @return mixed
     */
    public function getStylingClassAttribute()
    {
        if ($this->total > $this->total_ifs) {
            return 'text-danger';
        }

        if ($this->total_ifs > $this->total) {
            return 'text-success';
        }

        return 'text-primary';
    }

    /**
     * Return the difference
     *
     * @return type
     */
    public function getDifferenceAttribute()
    {
        return abs($this->total - $this->total_ifs);
    }

    /**
     * Return the difference
     *
     * @return type
     */
    public function getDifferenceFormattedAttribute()
    {
        if ($this->total > $this->total_ifs) {
            return '-' . number_format($this->difference, 2);
        } elseif ($this->total_ifs > $this->total) {
            return '+' . number_format($this->difference, 2);
        } else {
            return number_format($this->difference, 2);
        }
    }

    /**
     * Get the SCS VAT code to use for this line.
     *
     * @return string
     */
    public function getScsVatCodeAttribute()
    {
        // Duty invoice (zero)
        if ($this->purchaseInvoice->type == 'D') {
            return 'Z';
        }

        foreach ($this->charges as $charge) {
            if ($charge->hasDescription('VAT') || $charge->vat_applied) {
                return 1;
            }
        }

        // Default return (zero)
        return 'Z';
    }

    /**
     * Determine the line "type" - domestic / import / export
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        if ($this->sender_country_code == 'GB' && $this->recipient_country_code == 'GB') {
            return 'Domestic';
        }

        if ($this->sender_country_code != 'GB' && $this->recipient_country_code == 'GB') {
            return 'Import';
        }

        return 'Export';
    }

    /**
     * Service type detail.
     *
     * @return type
     */
    public function getServiceAttribute()
    {
        $service = $this->purchaseInvoice->carrier->services->where('carrier_code', $this->carrier_service)->first();

        if ($service) {
            return $service->carrier_name . ' (' . $this->carrier_service . ')';
        }

        return $this->carrier_service;
    }

    /**
     * Packaging type detail.
     *
     * @return type
     */
    public function getPackagingTypeAttribute()
    {
        $packagingType = $this->purchaseInvoice->carrier->packagingTypes->where('code', $this->carrier_packaging_code)->first();

        if ($packagingType) {
            return $packagingType->rate_code . ' (' . $this->carrier_packaging_code . ')';
        }

        return $this->carrier_packaging_code;
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('purchase_invoice_lines.carrier_consignment_number', '=', $filter)
                            ->orWhere('purchase_invoice_lines.scs_job_number', '=', $filter);
        }
    }

    /**
     * Scope carrier.
     *
     * @return
     */
    public function scopeHasCarrier($query, $carrierId)
    {
        if (is_numeric($carrierId)) {

            if (!isJoined($query, 'purchase_invoices')) {
                $query->join('purchase_invoices', 'purchase_invoice_lines.purchase_invoice_id', '=', 'purchase_invoices.id');
            }

            return $query->select('purchase_invoice_lines.*')->where('carrier_id', $carrierId);
        }
    }

    /**
     * Scope ship date.
     *
     * @return
     */
    public function scopeShipDateBetween($query, $dateFrom, $dateTo)
    {
        if (!$dateFrom && $dateTo) {
            return $query->where('ship_date', '<=', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && !$dateTo) {
            return $query->where('ship_date', '>=', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('ship_date', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

    /**
     * Scope ship date.
     *
     * @return
     */
    public function scopeInvoiceDateBetween($query, $dateFrom, $dateTo)
    {

        if (!isJoined($query, 'purchase_invoices')) {
            $query->select('purchase_invoice_lines.*')
                    ->join('purchase_invoices', 'purchase_invoice_lines.purchase_invoice_id', '=', 'purchase_invoices.id');
        }

        if (!$dateFrom && $dateTo) {
            return $query->where('purchase_invoices.date', '<=', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && !$dateTo) {
            return $query->where('purchase_invoices.date', '>=', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('purchase_invoices.date', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

}
