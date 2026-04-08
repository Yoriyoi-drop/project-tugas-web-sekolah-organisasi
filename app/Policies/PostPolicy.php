<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view posts
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // All authenticated users can view published posts
        // Admins and editors can view all posts
        if ($user->is_admin || $user->hasRole('editor')) {
            return true;
        }

        // Only allow viewing published posts
        return $post->is_published || $post->author === $user->name;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Users with 'create_posts' ability, admins, or editors can create posts
        return $user->hasAbility('create_posts') || $user->is_admin || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Admins and editors can update any post
        if ($user->is_admin || $user->hasRole('editor')) {
            return true;
        }

        // Authors can update their own posts
        return $post->author === $user->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Admins and editors can delete any post
        if ($user->is_admin || $user->hasRole('editor')) {
            return true;
        }

        // Authors can delete their own posts
        return $post->author === $user->name;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        // Only admins and editors can restore posts
        return $user->is_admin || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        // Only admins can permanently delete posts
        return $user->is_admin;
    }
    
    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Post $post): bool
    {
        // Admins and editors can publish posts
        return $user->is_admin || $user->hasRole('editor');
    }
}
