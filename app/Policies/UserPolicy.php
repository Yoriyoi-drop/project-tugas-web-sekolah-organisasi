<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin users can view all users
        return $user->is_admin || $user->hasAbility('view_users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile
        // Admins can view any user
        // Users with 'view_users' ability can view any user
        return $user->id === $model->id || 
               $user->is_admin || 
               $user->hasAbility('view_users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin users can create other users
        return $user->is_admin || $user->hasAbility('create_users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile
        // Admins can update any user
        // Users with 'edit_users' ability can update any user
        return $user->id === $model->id || 
               $user->is_admin || 
               $user->hasAbility('edit_users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admin users can delete other users
        // Prevent users from deleting their own account
        return $user->is_admin && $user->id !== $model->id || 
               $user->hasAbility('delete_users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only admin users can restore users
        return $user->is_admin || $user->hasAbility('restore_users');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admin users can permanently delete users
        return $user->is_admin || $user->hasAbility('force_delete_users');
    }
    
    /**
     * Determine whether the user can update profile settings.
     */
    public function updateProfile(User $user, User $model): bool
    {
        // Users can update their own profile settings
        return $user->id === $model->id;
    }
    
    /**
     * Determine whether the user can update security settings.
     */
    public function updateSecurity(User $user, User $model): bool
    {
        // Users can update their own security settings
        return $user->id === $model->id;
    }
    
    /**
     * Determine whether the user can assign roles.
     */
    public function assignRoles(User $user, User $model): bool
    {
        // Only admin users can assign roles
        return $user->is_admin || $user->hasAbility('assign_roles');
    }
}
