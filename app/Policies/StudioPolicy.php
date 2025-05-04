<?php

namespace App\Policies;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudioPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Studio $studio)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->roles()->where('name', 'editor')->exists() || $user->roles()->where('name', 'admin')->exists();
    }

    public function update(User $user, Studio $studio)
    {
        return $user->roles()->where('name', 'editor')->exists() || $user->roles()->where('name', 'admin')->exists();
    }

    public function delete(User $user, Studio $studio)
    {
        return $user->roles()->where('name', 'admin')->exists(); // Maybe only admins can delete studios
    }

    public function restore(User $user, Studio $studio)
    {
        return false;
    }

    public function forceDelete(User $user, Studio $studio)
    {
        return false;
    }
}