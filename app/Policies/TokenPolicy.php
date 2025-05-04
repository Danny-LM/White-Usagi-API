<?php

namespace App\Policies;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Auth\Access\HandlesAuthorization;

class TokenPolicy
{
    use HandlesAuthorization;

    public function revokeToken(User $user, PersonalAccessToken $token)
    {
        return $token->tokenable_id === $user->id;
    }
}
