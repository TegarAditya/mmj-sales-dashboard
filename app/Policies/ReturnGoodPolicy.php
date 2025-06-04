<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReturnGood;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnGoodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_return::good');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReturnGood $returnGood): bool
    {
        return $user->can('view_return::good');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_return::good');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReturnGood $returnGood): bool
    {
        return $user->can('update_return::good');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReturnGood $returnGood): bool
    {
        return $user->can('delete_return::good');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_return::good');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ReturnGood $returnGood): bool
    {
        return $user->can('force_delete_return::good');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_return::good');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ReturnGood $returnGood): bool
    {
        return $user->can('restore_return::good');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_return::good');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ReturnGood $returnGood): bool
    {
        return $user->can('replicate_return::good');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_return::good');
    }
}
