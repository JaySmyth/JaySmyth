<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return boolean
     */
    public function before(User $user)
    {
        if (!$user->hasPermission('view_reports')) {
            return false;
        }
    }

    /**
     * Index policy.
     *
     * @return boolean
     */
    public function index(User $user)
    {
        if ($user->hasPermission('view_reports')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function fedexCustoms(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_fedex_customs_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function shippers(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_shippers_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function nonShippers(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_non_shippers_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function scanning(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_scanning_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function dims(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_dim_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function activeShipments(User $user)
    {
        if ($user->hasPermission('view_active_shipments_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function pod(User $user)
    {
        if ($user->hasPermission('view_pod_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function exceptions(User $user)
    {
        if ($user->hasPermission('view_exceptions_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function unknownJobs(User $user)
    {
        if ($user->hasPermission('view_unknown_jobs_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function dailyStats(User $user)
    {
        if ($user->hasPermission('view_daily_stats_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function fedexInternationalAvailable(User $user)
    {
        if ($user->hasPermission('view_fedex_international_available_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function margins(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_margins_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function carrierScans(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_carrier_scans_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function purchaseInvoiceLines(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_purchase_invoice_lines_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function preTransit(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_pre_transit_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function hazardous(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_hazardous_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function shipmentsByCarrier(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_shipments_by_carrier_report')) {
            return true;
        }
    }

    /**
     * View report.
     * 
     * @return boolean
     */
    public function collectionSettings(User $user)
    {
        if ($user->hasIfsRole() && $user->hasPermission('view_collection_settings_report')) {
            return true;
        }
    }

}
