<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow if user has 'manage_students' ability or is admin
        return $user->hasAbility('manage_students') || $user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        // Allow if user has 'manage_students' ability, is admin, or owns the record
        return $user->hasAbility('manage_students') || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow if user has 'manage_students' ability or is admin
        return $user->hasAbility('manage_students') || $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        // Allow if user has 'manage_students' ability or is admin
        return $user->hasAbility('manage_students') || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        // Allow if user has 'manage_students' ability or is admin
        return $user->hasAbility('manage_students') || $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        // Allow if user has 'manage_students' ability or is admin
        return $user->hasAbility('manage_students') || $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        // Allow if user has 'manage_students' ability or is admin
        return $user->hasAbility('manage_students') || $user->is_admin;
    }
}
