<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ShipmentScopes
{
    /**
     * Scope ship date.
     *
     * @return
     */
    public function scopeShipDateBetween($query, $dateFrom, $dateTo)
    {
        if (! $dateFrom && $dateTo) {
            return $query->where('ship_date', '<', Carbon::parse($dateTo)->endOfDay());
        }

        if ($dateFrom && ! $dateTo) {
            return $query->where('ship_date', '>', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateFrom && $dateTo) {
            return $query->whereBetween('ship_date', [Carbon::parse($dateFrom)->startOfDay(), Carbon::parse($dateTo)->endOfDay()]);
        }
    }

    /**
     * Scope filter.
     *
     * @return
     */
    public function scopeFilter($query, $filter)
    {
        if ($filter) {
            $filter = trim($filter);

            return $query->where('consignment_number', $filter)
                ->orWhere('carrier_consignment_number', $filter)
                ->orWhere('carrier_tracking_number', $filter)
                ->orWhere('shipment_reference', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope SCS job number.
     *
     * @return
     */
    public function scopeHasScsJobNumber($query, $scsJobNumber)
    {
        if ($scsJobNumber) {
            $scsJobNumber = trim($scsJobNumber);

            return $query->where('scs_job_number', $scsJobNumber);
        }
    }

    /**
     * Scope recipient filter.
     *
     * @return
     */
    public function scopeRecipientFilter($query, $filter)
    {
        if ($filter) {
            $filter = trim($filter);

            return $query->where('recipient_company_name', 'LIKE', '%'.$filter.'%')
                ->orWhere('recipient_name', 'LIKE', '%'.$filter.'%')
                ->orWhere('recipient_address1', 'LIKE', '%'.$filter.'%')
                ->orWhere('recipient_city', 'LIKE', '%'.$filter.'%')
                ->orWhere('recipient_postcode', 'LIKE', '%'.$filter.'%');
        }
    }

    /**
     * Scope mode.
     *
     * @return
     */
    public function scopeHasMode($query, $mode)
    {
        if (is_numeric($mode)) {
            return $query->where('shipments.mode_id', $mode);
        }

        if ($mode) {
            return $query->select('shipments.*')
                ->join('modes', 'shipments.mode_id', '=', 'modes.id')
                ->where('modes.name', '=', $mode);
        }
    }

    /**
     * Scope status.
     *
     * @return
     */
    public function scopeHasStatus($query, $status)
    {
        // Shipped (all received shipments - not cancelled)
        if ($status == 'S') {
            return $query->whereReceived(1)->where('status_id', '!=', 7);
        }

        if (is_numeric($status)) {
            return $query->where('status_id', $status);
        }

        if ($status) {
            $query->select('shipments.*')->join('statuses', 'shipments.status_id', '=', 'statuses.id');

            if (is_array($status)) {
                return $query->whereIn('statuses.code', $status);
            }

            return $query->where('statuses.code', '=', $status);
        }
    }

    /**
     * Scope company.
     *
     * @return
     */
    public function scopeHasCompany($query, $companyId)
    {
        if (is_numeric($companyId)) {
            return $query->where('company_id', $companyId);
        }
    }

    /**
     * Scope depot.
     *
     * @return
     */
    public function scopeHasDepot($query, $depotId)
    {
        if (is_numeric($depotId)) {
            return $query->where('shipments.depot_id', $depotId);
        }
    }

    /**
     * Scope created by user.
     *
     * @return
     */
    public function scopeCreatedBy($query, $userId)
    {
        if (is_numeric($userId)) {
            return $query->where('user_id', $userId);
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
            return $query->where('shipments.carrier_id', $carrierId);
        }
    }

    /**
     * Scope manifest.
     *
     * @return
     */
    public function scopeHasManifest($query, $manifestId)
    {
        if (is_numeric($manifestId)) {
            return $query->where('manifest_id', $manifestId);
        }
    }

    /**
     * Scope route.
     *
     * @return
     */
    public function scopeHasRoute($query, $routeId)
    {
        if (is_numeric($routeId)) {
            return $query->where('route_id', $routeId);
        }
    }

    /**
     * Scope service.
     *
     * @return
     */
    public function scopeHasService($query, $service)
    {
        if (is_numeric($service)) {
            return $query->where('service_id', $service);
        }

        if ($service) {
            return $query->select('shipments.*')
                ->join('services', 'shipments.service_id', '=', 'services.id')
                ->where('services.code', '=', $service);
        }
    }

    /**
     * Scope shipment has sender country code.
     *
     * @return
     */
    public function scopeHasSource($query, $countryCode)
    {
        if ($countryCode) {
            return $query->where('sender_country_code', $countryCode);
        }
    }

    /**
     * Scope shipment has recipient country code.
     *
     * @return
     */
    public function scopeHasDestination($query, $countryCode)
    {
        if ($countryCode) {
            return $query->where('recipient_country_code', $countryCode);
        }
    }

    /**
     * Scope shipment has sender country code.
     *
     * @return
     */
    public function scopeHasRecipientType($query, $type)
    {
        if ($type) {
            return $query->where('recipient_type', $type);
        }
    }

    /**
     * Scope shipment has sender country code.
     *
     * @return
     */
    public function scopeTraffic($query, $traffic)
    {
        switch ($traffic) {
            case 'D':
                // 'Domestic (same country)
                return $this->scopeIsDomestic($query);
            case 'EU':
                // European Union
                return $this->scopeWithinEu($query);
            case 'ED':
                // European Union Excluding UK Domestic
                return $this->scopeEuExcludingUkDomestic($query);
            case 'I':
                // International (all non UK Domestic)
                return $this->scopeIsInternational($query);
            case 'N':
                // Non EU (everything outside of EU)
                return $this->scopeNotEu($query);
            case 'UD':
                // UK Domestic
                return $this->scopeIsUkDomestic($query);
        }
    }

    /**
     * Scope shipment is UK domestic.
     *
     * @return type
     */
    public function scopeIsDomestic($query)
    {
        return $query->where('sender_country_code', '=', DB::raw('recipient_country_code'));
    }

    public function scopeWithinEu($query)
    {
        if (! isJoined($query, 'countries')) {
            $query->join('countries', 'shipments.recipient_country_code', '=', 'countries.country_code');
        }

        return $query->where('countries.eu', 1);
    }

    /*
     * Scope restrict results by company.
     *
     */

    public function scopeEuExcludingUkDomestic($query)
    {
        if (! isJoined($query, 'countries')) {
            $query->join('countries', 'shipments.recipient_country_code', '=', 'countries.country_code');
        }

        return $query->where('countries.eu', 1)
            ->whereNotIn('recipient_country_code', getUkDomesticCountries());
    }

    /*
     * Scope restrict results by mode.
     *
     */

    /**
     * Scope shipment is international.
     *
     * @return type
     */
    public function scopeIsInternational($query)
    {
        return $query->whereIn('sender_country_code', getUkDomesticCountries())
            ->whereNotIn('recipient_country_code', getUkDomesticCountries());
    }

    /*
     * Scope restrict by depot.
     *
     */

    public function scopeNotEu($query)
    {
        if (! isJoined($query, 'countries')) {
            $query->join('countries', 'shipments.recipient_country_code', '=', 'countries.country_code');
        }

        return $query->where('countries.eu', 0);
    }

    /*
     * Get the shipments available for manifesting.
     */

    /**
     * Scope shipment is domestic.
     *
     * @return type
     */
    public function scopeIsUkDomestic($query)
    {
        return $query->whereIn('sender_country_code', getUkDomesticCountries())
            ->whereIn('recipient_country_code', getUkDomesticCountries());
    }

    /*
     * Scope recipient not EU.
     *
     */

    /**
     * Scope single/multi-piece.
     *
     * @return
     */
    public function scopeHasPieces($query, $pieces)
    {
        if (is_numeric($pieces)) {
            if ($pieces == 1) {
                return $query->where('pieces', $pieces);
            }

            return $query->where('pieces', '>', 1);
        }
    }

    /*
     * Scope recipient EU.
     *
     */

    /**
     * Scope customs value.
     *
     * @return
     */
    public function scopeHasCustomsValueBetween($query, $low, $high)
    {
        if (is_numeric($low) && is_numeric($high)) {
            return $query->whereBetween('customs_value', [$low, $high]);
        }
    }

    /*
     * Scope recipient EU.
     *
     */

    public function scopeRestrictCompany($query, $companyIds)
    {
        if (is_numeric($companyIds)) {
            return $query->whereCompanyId($companyIds);
        }

        return $query->whereIn('company_id', $companyIds);
    }

    public function scopeRestrictMode($query, $modeIds)
    {
        return $query->whereIn('shipments.mode_id', $modeIds);
    }

    public function scopeRestrictDepot($query, $depotIds)
    {
        return $query->whereIn('shipments.depot_id', $depotIds);
    }

    public function scopeAvailableForManifesting($query)
    {
        return $query->whereReceived(1)->whereNull('manifest_id')->whereNotIn('status_id', [1, 7]);
    }

    /**
     * Scope shipment is UK domestic.
     *
     * @return type
     */
    public function scopeNotDomestic($query)
    {
        return $query->where('sender_country_code', '!=', DB::raw('recipient_country_code'));
    }

    /**
     * Scope shipment is international.
     *
     * @return type
     */
    public function scopeNotUkDomestic($query)
    {
        return $query->whereNotIn('recipient_country_code', getUkDomesticCountries());
    }

    /*
     * Scope Fedex Route Charles de Gaulle.
     *
     */

    public function scopeFedexRouteParis($query)
    {
        if (! isJoined($query, 'countries')) {
            $query->join('countries', 'shipments.recipient_country_code', '=', 'countries.country_code');
        }

        return $query->where('countries.fedex_route', 1);
    }

    /*
     * Scope FedEx Route Memphis
     *
     */

    public function scopeFedexRouteMemphis($query)
    {
        if (! isJoined($query, 'countries')) {
            $query->join('countries', 'shipments.recipient_country_code', '=', 'countries.country_code');
        }

        return $query->where('countries.fedex_route', 2);
    }

    /*
     * Scope has service in.
     *
     */

    public function scopeHasServiceIn($query, $services = [])
    {
        if (count($services) > 0) {
            return $query->whereIn('service_id', $services);
        }
    }

    /*
     * Scope isActive - not delivered or cancelled, but received.
     *
     */

    public function scopeIsActive($query)
    {
        return $query->whereDelivered(0)
            ->whereReceived(1)
            ->whereNotIn('status_id', [7, 9, 10, 11, 17]);
    }

    /*
     * Scope shipments that are eligible for invoicing
     *
     */

    public function scopeUninvoiced($query)
    {

        // Exclude IFS Demo accounts and Unit Test Account
        $excludedCompanies = ['57', '508', '849', '707'];

        if (! isJoined($query, 'companies')) {
            $query->join('companies', 'shipments.company_id', '=', 'companies.id');
        }

        return $query->whereReceived(1)
            ->whereInvoicingStatus(0)
            ->whereNull('scs_job_number')
            ->whereNull('invoice_run_id')
            ->where('bill_shipping', 'sender')
            ->where('mode_id', 1)
            ->where('companies.legacy_pricing', 0)
            ->where('status_id', '<>', 7)
            ->where('shipments.id', '>', 640711)
            ->whereNotIn('companies.id', $excludedCompanies);
    }

    /**
     * Scope department.
     *
     * @return
     */
    public function scopeHasDepartment($query, $departmentId)
    {
        if (is_numeric($departmentId)) {
            return $query->where('department_id', $departmentId);
        }
    }

    /*
     * Scope FedEx intl collect.
     *
     */

    public function scopeIsFedexCollect($query)
    {
        return $query->where('bill_shipping', '!=', 'sender')
            ->where('bill_shipping_account', '!=', 205691588)
            ->where('bill_shipping_account', '!=', '')
            ->where('carrier_id', 2)
            ->whereIn('sender_country_code', getUkDomesticCountries())
            ->whereNotIn('recipient_country_code', getUkDomesticCountries());
    }

    /*
     * Scope NOT FedEx intl collect.
     *
     */
    public function scopeIsNotFedexCollect($query)
    {
        return $query->where('bill_shipping', '!=', 'recipient')
            //->where('bill_shipping_account', 205691588)
            ->where('carrier_id', 2)
            ->whereIn('sender_country_code', getUkDomesticCountries())
            ->whereNotIn('recipient_country_code', getUkDomesticCountries());
    }

    /*
    * Scope Manifest number.
    *
    */
    public function scopeHasManifestNumber($query, $manifestNumber)
    {
        if ($manifestNumber) {
            $manifestNumber = trim($manifestNumber);

            if (! isJoined($query, 'manifests')) {
                $query->join('manifests', 'shipments.manifest_id', '=', 'manifests.id');
            }

            return $query->where('manifests.number', $manifestNumber);
        }
    }
}
