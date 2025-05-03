<?php

namespace App\Policies;

use App\Models\Anime;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true; // Right now, all users can view the list of animes
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anime  $anime
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Anime $anime)
    {
        return true; // Right now, all users can view a specific anime
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Righ now, we allow all authenticated users to create animes
        return true;
        // Future implement to verify is the user is admin
        // return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anime  $anime
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Anime $anime)
    {
        // Right now, we allow all authenticated users to update animes
        return true;
        // Future implement to verify is the user is admin
        // return $user->id === $anime->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anime  $anime
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Anime $anime)
    {
        // Right now, we allow all authenticated users to delete animes
        return true;
        // Future implement to verify is the user is admin
        // return $user->id === $anime->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anime  $anime
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Anime $anime)
    {
        return false; // Future implement to restore 
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anime  $anime
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Anime $anime)
    {
        return false; // Future implement to permanently delete
    }
}
