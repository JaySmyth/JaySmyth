<?php

namespace App\Policies;

use App\User;
use App\CustomsEntry;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomsEntryPolicy
{

    use HandlesAuthorization;

    /**
     * Index policy.
     *
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function addCommodity(User $user, CustomsEntry $customsEntry)
    {
        if ($user->hasIfsRole() && $user->hasPermission('create_customs_entry') && $user->relatedTo($customsEntry)) {
            return true;
        }
    }

}
