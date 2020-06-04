<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return bool
     */
    public function before(User $user)
    {
        if (! $user->hasIfsRole()) {
            return false;
        }
    }

    /**
     * Index policy.
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        if ($user->hasPermission('view_quotation')) {
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
        if ($user->hasPermission('view_quotation')) {
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
        if ($user->hasPermission('create_quotation')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user)
    {
        if ($user->hasPermission('edit_quotation')) {
            return true;
        }
    }

    /**
     * Set status of quotation.
     *
     * @return bool
     */
    public function status(User $user)
    {
        if ($user->hasPermission('create_quotation')) {
            return true;
        }
    }

    /**
     * View PDF.
     *
     * @return bool
     */
    public function pdf(User $user)
    {
        if ($user->hasPermission('view_quotation')) {
            return true;
        }
    }

    /**
     * Delete quotation.
     *
     * @return bool
     */
    public function delete(User $user, Quotation $quotation)
    {
        if ($user->hasPermission('delete_quotation') && $quotation->user_id == $user->id) {
            return true;
        }
    }
}
