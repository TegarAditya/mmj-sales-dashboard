<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Estimation;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstimationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_estimation');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estimation $estimation): bool
    {
        return $user->can('view_estimation');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_estimation');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Estimation $estimation): bool
    {
        return $user->can('update_estimation');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Estimation $estimation): bool
    {
        return $user->can('delete_estimation');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_estimation');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Estimation $estimation): bool
    {
        return $user->can('force_delete_estimation');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_estimation');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Estimation $estimation): bool
    {
        return $user->can('restore_estimation');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_estimation');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Estimation $estimation): bool
    {
        return $user->can('replicate_estimation');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_estimation');
    }
}
