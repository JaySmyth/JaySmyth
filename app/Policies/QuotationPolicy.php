<?php

namespace App\Policies;

use App\User;
use App\Quotation;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{

    use HandlesAuthorization;

    /**
     * Intercept all checks - ensure IFS user.
     *
     * @return boolean
     */
    public function before(User $user)
    {
        if (!$user->hasIfsRole()) {
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
        if ($user->hasPermission('view_quotation')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function delete(User $user, Quotation $quotation)
    {
        if ($user->hasPermission('delete_quotation') && $quotation->user_id == $user->id) {
            return true;
        }
    }

}
