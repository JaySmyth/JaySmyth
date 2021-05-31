<?php

namespace App\Models;

use App\Traits\PurchaseInvoiceToMultifreightXml;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use PurchaseInvoiceToMultifreightXml;

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
    protected $dates = ['date', 'date_received', 'created_at', 'updated_at'];

    /**
     * A purchase invoice is owned by a carrier.
     *
     * @return
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * A purchase invoice has many lines.
     *
     * @return
     */
    public function lines()
    {
        return $this->hasMany(PurchaseInvoiceLine::class)->with('purchaseInvoice', 'shipment', 'charges')->orderBy('sender_company_name')->orderBy('ship_date')->orderBy('carrier_consignment_number');
    }

    /**
     * A purchase invoice has many lines.
     *
     * @return
     */
    public function linesWithScsJob()
    {
        return $this->hasMany(PurchaseInvoiceLine::class)->with('purchaseInvoice', 'shipment', 'charges', 'scsJob')->orderBy('sender_company_name')->orderBy('ship_date')->orderBy('carrier_consignment_number');
    }

    /**
     * A purchase invoice has many charges.
     *
     * @return
     */
    public function charges()
    {
        return $this->hasMany(PurchaseInvoiceCharge::class);
    }

    /**
     * Invoice lines grouped by overcharge (negative / positive variances).
     *
     * @return type
     */
    public function getCostComparisons()
    {
        $lines['negative'] = $this->linesWithScsJob->where('overcharge', 1);
        $lines['positive'] = $this->linesWithScsJob->where('overcharge', 0);

        return $lines;
    }

    /**
     * Return lines with a negative variances (overcharge).
     *
     * @return type
     */
    public function getNegativeVariances()
    {
        return $this->linesWithScsJob->where('overcharge', 1);
    }

    /**
     * Get verbose status.
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'Passed';
            case 2:
                return 'Processed';
            default:
                return 'Match Failed';
        }
    }

    public function getStatusClassAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'text-caution';
            case 2:
                return 'text-success';
            default:
                return 'text-danger';
        }
    }

    /**
     * Get the supplier code used within SCS application.
     *
     * @return string
     */
    public function getScsSupplierCodeAttribute()
    {
        switch ($this->carrier_id) {
            case 2:
                // FedEx International - Duty invoice
                if ($this->type == 'D') {
                    return '0170759';
                }

                // FedEx UK supplier code
                if ($this->account_number == '811732648' || $this->account_number == 'MCA-118740') {
                    return '0159965';
                }

                // FedEx UK supplier code for Glendimplex
                if ($this->account_number == '811250724' || $this->account_number == 'MCA-506534') {
                    return '0173502';
                }

                // Default to FedEx Intl supplier code
                return '0109956';

            case 3:
                return '0169978'; // UPS
            case 4:
                return '0103690'; // TNT
            case 5:
                return '0101610'; // DHL
            case 12:
                return '0193035'; // Primary Freight
            case 14:
            case 15:
                return '0171994'; // Express Freight
            case 16:
                return '0198859'; // XDP
            case 17:
                // DX - Decoras account
                if ($this->account_number == '14631619') {
                    return '0200076';
                }

                return '0200187'; // DX
            default:
                return 'UNKNOWN';
        }
    }

    /**
     * Set the invoice type.
     *
     * @param type $type
     */
    public function setType($type)
    {
        switch ($this->carrier->code) {
            case 'fedex':
                switch ($type) {
                    case 'I':
                    case 'FR':
                        $invoiceType = 'F'; // freight
                        // no break
                    case 'C':
                    case 'DT':
                        $invoiceType = 'D'; // duty & taxes
                }
                break;
            case 'ups':
                switch ($type) {
                    case '01':
                        $invoiceType = 'F'; // freight
                        // no break
                    case '06':
                    case '12':
                        $invoiceType = 'D'; // duty & taxes
                }
                break;
            case 'dhl':
                $invoiceType = 'F'; // freight
                // no break
            default:
                $invoiceType = 'O'; // other
        }
        $this->type = $invoiceType;
        $this->save();
    }

    /**
     * Sets additional values on the invoice.
     */
    public function setAdditionalValues()
    {
        $this->setScsJobNumbersAndShipmentIds();
        $this->setImportExport();
        $this->autoPass();
    }

    /**
     * Update the SCS job numbers and shipment IDs for all invoice lines.
     */
    public function setScsJobNumbersAndShipmentIds()
    {
        foreach ($this->lines as $line) {
            if (! $line->carrier_tracking_number) {
                continue;
            }

            if ($this->carrier_id == 14) {
                $shipment = Shipment::whereIn('carrier_id', [14, 15])->whereCarrierTrackingNumber($line->carrier_tracking_number)->first();
            } else {
                $shipment = Shipment::whereCarrierId($this->carrier_id)->whereCarrierTrackingNumber($line->carrier_tracking_number)->first();
            }

            if ($shipment) {
                if (preg_match('/[a-zA-Z]{6}[0-9]{8}/', $shipment->scs_job_number)) {
                    $line->scs_job_number = $shipment->scs_job_number;
                }

                $line->shipment_id = $shipment->id;

                if ($this->carrier_id == 5) {
                    $line->weight = $shipment->weight;
                    $line->delivery_date = $shipment->delivery_date;
                }

                $line->save();
            } else {

                // Check job header for SCS job number
                $withHypen = substr($line->carrier_tracking_number, 0, 3).'-'.substr($line->carrier_tracking_number, 3);

                $jobHdr = \App\Multifreight\JobHdr::select('job_disp')
                    ->orWhere('hawb_char', $line->carrier_tracking_number)
                    ->orWhere('hawb_char', $withHypen)
                    ->orWhere('mawb_char', $line->carrier_tracking_number)
                    ->orWhere('mawb_char', $withHypen)
                    ->first();

                if ($jobHdr) {
                    $line->scs_job_number = $jobHdr->job_disp;
                    $line->save();
                }
            }
        }
    }

    /**
     * Set import / export flag for duty invoices.
     */
    public function setImportExport()
    {
        if ($this->type == 'D') {
            $line = $this->lines->first();

            if ($line) {
                if (($line->sender_country_code == 'GB' && $line->recipient_country_code == 'GB') || ($line->sender_country_code == 'GB' && $line->recipient_country_code != 'GB')) {
                    $this->import_export = 'E';
                } else {
                    $this->import_export = 'I';
                }

                $this->save();
            }
        }
    }

    /**
     * Check every line of an invoice and set to passed if there are no overcharges.
     * Called automatically after an invoice has been imported.
     */
    public function autoPass()
    {
        if ($this->status == 0) {
            $pass = true;
            foreach ($this->lines as $line) {
                if ($line->overcharge) {
                    $pass = false;
                    break;
                }
            }

            if ($pass) {
                $this->status = 1;
                $this->costs = 1;
                $this->save();
            }
        }
    }

    /**
     * Set the total taxable amount for this invoice.
     */
    public function setTotalTaxable()
    {
        $this->total_taxable = $this->charges->where('vat_applied', 1)->sum('billed_amount');
        $this->save();
    }

    /**
     * Set the total taxable amount for this invoice.
     */
    public function setTotalNonTaxable()
    {
        $this->total_non_taxable = $this->charges->where('vat_applied', 0)->sum('billed_amount');
        $this->save();
    }

    /**
     * Set the invoice status to (1)Passed. If any overcharge present, update
     * each line that has a negative variance with the user ID that passed the invoice.
     *
     * @param type $userId
     */
    public function setPassed($userId = false)
    {
        if ($this->status == 0) {
            foreach ($this->lines as $line) {
                if ($line->overcharge) {
                    $line->user_id = $userId;
                    $line->save();
                }
            }
            $this->status = 1;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function setExported()
    {
        if ($this->status == 1) {
            $this->exported = true;
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Set the received flag. Toggles true/false.
     */
    public function setReceived()
    {
        $this->date_received = ($this->received) ? null : Carbon::now();
        $this->received = ($this->received) ? false : true;
        $this->save();

        return $this->received;
    }

    /**
     * Set the queried flag. Toggles true/false.
     */
    public function setQueried()
    {
        $this->queried = ($this->queried) ? false : true;
        $this->save();

        return $this->queried;
    }

    /**
     * Set the costs flag. Toggles true/false.
     */
    public function setCosts()
    {
        $this->costs = ($this->costs) ? false : true;
        $this->save();

        return $this->costs;
    }

    /**
     * Set the copy docs flag. Toggles true/false.
     */
    public function setCopyDocs()
    {
        $this->copy_docs = ($this->copy_docs) ? false : true;
        $this->save();

        return $this->copy_docs;
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            return $query->where('invoice_number', 'LIKE', '%'.$filter.'%')
                ->orWhere('account_number', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeHasConsignmentOrScsJob($query, $consignment)
    {
        if ($consignment) {
            return $query->select('purchase_invoices.*')
                ->join('purchase_invoice_lines', 'purchase_invoices.id', '=', 'purchase_invoice_lines.purchase_invoice_id')
                ->where('purchase_invoice_lines.carrier_consignment_number', '=', $consignment)
                ->orWhere('purchase_invoice_lines.scs_job_number', '=', $consignment);
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
            return $query->where('carrier_id', $carrierId);
        }
    }

    /**
     * Scope status.
     *
     * @return
     */
    public function scopeHasStatus($query, $status)
    {
        if ($status == 'U') {
            return $query->whereIn('status', [0, 1]);
        }

        if (is_numeric($status)) {
            return $query->where('status', $status);
        }
    }

    /**
     * Scope import/export.
     *
     * @return
     */
    public function scopeHasType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
    }

    /**
     * Scope import/export.
     *
     * @return
     */
    public function scopeHasImportExport($query, $importExport)
    {
        if ($importExport) {
            return $query->where('import_export', $importExport);
        }
    }

    /**
     * Scope received.
     *
     * @return
     */
    public function scopeHasReceived($query, $received)
    {
        if (is_numeric($received)) {
            return $query->where('received', $received);
        }
    }

    /**
     * Scope received.
     *
     * @return
     */
    public function scopeHasQueried($query, $queried)
    {
        if (is_numeric($queried)) {
            return $query->where('queried', $queried);
        }
    }

    /**
     * Scope received.
     *
     * @return
     */
    public function scopeHasCosts($query, $costs)
    {
        if (is_numeric($costs)) {
            return $query->where('costs', $costs);
        }
    }

    /**
     * Scope received.
     *
     * @return
     */
    public function scopeHasCopyDocs($query, $copyDocs)
    {
        if ($copyDocs) {
            return $query->where('copy_docs', $copyDocs);
        }
    }

    /**
     * Scope ship date.
     *
     * @return
     */
    public function scopeDateBetween($query, $dateFrom, $dateTo)
    {
        if (! $dateFrom && $dateTo) {
            return $query->where('date', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && ! $dateTo) {
            return $query->where('date', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('date', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }
}
