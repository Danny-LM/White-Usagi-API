<?php

namespace App\Policies;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GenrePolicy
{
    use HandlesAuthorization;

    /**
     * 
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * 
     */
    public function view(User $user, Genre $genre)
    {
        return true;
    }

    /**
     * 
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'editor')->exists() || $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * 
     */
    public function update(User $user, Genre $genre)
    {
        return $user->roles()->where('name', 'editor')->exists() || $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * 
     */
    public function delete(User $user, Genre $genre)
    {
        return $user->roles()->where('name', 'editor')->exists() || $user->roles()->where('name', 'admin')->exists();
    }

    /**
     * 
     */
    public function restore(User $user, Genre $genre)
    {
        return false;
    }

    /**
     * 
     */
    public function forceDelete(User $user, Genre $genre)
    {
        return false;
    }
}