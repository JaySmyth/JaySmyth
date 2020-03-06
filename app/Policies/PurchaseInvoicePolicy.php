<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseInvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return bool
     */
    public function before(User $user)
    {
        if (! $user->hasIfsRole() && ! $user->hasPermission('view_purchase_invoice')) {
            return false;
        }
    }

    /**
     * Index policy.
     *
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->hasPermission('view_purchase_invoice')) {
            return true;
        }
    }

    /**
     * Toggle invoice flags policy.
     *
     * @return bool
     */
    public function flags(User $user)
    {
        if ($user->hasPermission('set_purchase_invoice_flags')) {
            return true;
        }
    }

    /**
     * Index policy.
     *
     * @return bool
     */
    public function admin(User $user)
    {
        if ($user->hasPermission('purchase_invoice_admin')) {
            return true;
        }
    }
}
