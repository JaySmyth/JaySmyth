<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransportJobPolicy
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
        if ($user->hasPermission('view_transport_job')) {
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
        if ($user->hasPermission('view_transport_job')) {
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
        if ($user->hasPermission('create_transport_job')) {
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
        if ($user->hasPermission('edit_transport_job')) {
            return true;
        }
    }

    /**
     * Allocate jobs to driver.
     *
     * @return bool
     */
    public function manifestJobs(User $user)
    {
        if ($user->hasPermission('view_unmanifested_jobs')) {
            return true;
        }
    }

    /**
     * Close job.
     *
     * @return bool
     */
    public function close(User $user)
    {
        if ($user->hasPermission('close_transport_job')) {
            return true;
        }
    }

    /**
     * Cancel job.
     *
     * @return bool
     */
    public function cancel(User $user)
    {
        if ($user->hasPermission('cancel_transport_job')) {
            return true;
        }
    }

    /**
     * Unmanifest - remove a transport job from a driver manifest.
     *
     * @return bool
     */
    public function unmanifest(User $user)
    {
        if ($user->hasPermission('unmanifest_transport_job')) {
            return true;
        }
    }
}
