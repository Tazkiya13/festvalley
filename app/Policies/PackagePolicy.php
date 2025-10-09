<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PackagePolicy
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
    public function view(User $user, Package $package)
    {
        if ($user->can('view package')) {
            return true;
        }
    }

        /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Admin can create any package
        if ($user->can('create package')) {
            return true;
        }
        
        // Artist can create their own package
        if ($user->can('create package artists')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Package $package)
    {
        // Admin can edit any package
        if ($user->can('edit package')) {
            return true;
        }
        
        // Artist can only edit their own packages
        if ($user->can('edit package artists') && $package->user_id == $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Package $package)
    {
        // Admin can delete any package
        if ($user->can('delete package')) {
            return true;
        }
        
        // Artist can only delete their own packages
        if ($user->can('delete package artists') && $package->user_id == $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Package $package): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Package $package): bool
    {
        return false;
    }
}
