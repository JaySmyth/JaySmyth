<?php

namespace App\Policies;

use App\CustomsEntry;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomsEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Index policy.
     *
     * @return bool
     */
    public function index(User $user)
    {
        if ($user->hasPermission('view_customs_entry')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return bool
     */
    public function view(User $user, CustomsEntry $customsEntry)
    {
        if ($user->hasPermission('view_customs_entry') && $user->relatedTo($customsEntry)) {
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
        if ($user->hasIfsRole() && $user->hasPermission('create_customs_entry')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return bool
     */
    public function update(User $user, CustomsEntry $customsEntry)
    {
        if ($user->hasIfsRole() && $user->hasPermission('create_customs_entry') && $user->relatedTo($customsEntry)) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return bool
     */
    public function delete(User $user, CustomsEntry $customsEntry)
    {
        if ($user->hasIfsRole() && $user->hasPermission('delete_customs_entry') && $user->relatedTo($customsEntry)) {
            return true;
        }
    }

    /**
     * Add commodity policy.
     *
     * @return bool
     */
    public function addCommodity(User $user, CustomsEntry $customsEntry)
    {
        if ($user->hasIfsRole() && $user->hasPermission('create_customs_entry') && $user->relatedTo($customsEntry)) {
            return true;
        }
    }
}
