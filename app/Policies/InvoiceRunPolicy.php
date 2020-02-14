<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceRunPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return bool
     */
    public function before(User $user)
    {
        if (! $user->hasIfsRole() && ! $user->hasPermission('view_invoice_run')) {
            return false;
        }
    }

    /**
     * Index policy.
     *
     * @return bool
     */
    public function index(User $user)
    {
        if ($user->hasPermission('view_invoice_run')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->hasPermission('view_invoice_run')) {
            return true;
        }
    }

    /**
     * Create policy.
     *
     * @return bool
     */
    public function create(User $user)
    {
        if ($user->hasPermission('create_invoice_run')) {
            return true;
        }
    }
}
