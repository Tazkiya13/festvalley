<?php

namespace App\Policies;

use App\Models\Email;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user)
    {
        if ($user->can('view customer order')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user)
    // {
    //     if ($user->can('create customer order')) {
    //         return true;
    //     }

    //     return Response::deny('You do not have permission to create orders.');
    // }

    /**
     * Determine whether the user can update the model.
     */
    // public function update(User $user)
    // {
    //     if ($user->can('edit customer order')) {
    //         return true;
    //     }

    //     return Response::deny('You do not have permission to edit orders.');
    // }

    public function approve(User $user)
    {
        if ($user->can('approve customer order')) {
            return true;
        }

        return Response::deny('You do not have permission to approve orders.');
    }

    public function reject(User $user)
    {
        if ($user->can('reject customer order')) {
            return true;
        }

        return Response::deny('You do not have permission to reject orders.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user)
    {
        if ($user->can('delete customer order')) {
            return true;
        }

        return Response::deny('You do not have permission to delete orders.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Email $email): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Email $email): bool
    {
        return false;
    }
}
