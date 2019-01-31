<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    use HandlesAuthorization;

    /**
     * Index policy.
     *
     * @return boolean
     */
    public function index(User $user)
    {
        if ($user->hasPermission('view_user')) {
            return true;
        }
    }

    /**
     * Show policy.
     *
     * @return boolean
     */
    public function view(User $user, User $otherUser)
    {
        if ($user->hasPermission('view_user') && $user->relatedTo($otherUser)) {
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
        if ($user->hasPermission('create_user')) {
            return true;
        }
    }

    /**
     * Update policy.
     *
     * @return boolean
     */
    public function update(User $user, User $otherUser)
    {
        if ($user->hasPermission('update_user') && $user->relatedTo($otherUser)) {
            return true;
        }
    }

    /**
     * Delete policy.
     *
     * @return boolean
     */
    public function delete(User $user, User $otherUser)
    {
        if ($user->hasPermission('delete_user') && $user->relatedTo($otherUser)) {
            return true;
        }
    }

    /**
     * Add company policy.
     *
     * @return boolean
     */
    public function addCompany(User $user, User $otherUser)
    {
        if ($user->hasPermission('add_company') && $user->relatedTo($otherUser)) {
            return true;
        }
    }

    /**
     * Remove company policy.
     *
     * @return boolean
     */
    public function removeCompany(User $user, User $otherUser)
    {
        if ($user->hasPermission('remove_company') && $user->relatedTo($otherUser)) {
            return true;
        }
    }

    /**
     * Reset password policy.
     *
     * @return boolean
     */
    public function resetPassword(User $user, User $otherUser)
    {

        if ($user->hasPermission('reset_password') && $user->relatedTo($otherUser)) {
            return true;
        }
    }

}
