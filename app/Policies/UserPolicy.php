<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $authUser, User $targetUser): bool
    {
        // Ejemplo: solo si tiene permiso específico
        return $authUser->can('users.edit');
    }

    /**
     * Determine if the user can deactivate another user.
     */
    public function deactivate(User $authUser, User $targetUser): bool
    {
        //No permitir desactivarse a sí mismo
        if ($authUser->id === $targetUser->id) {
            return false;
        }

        return $authUser->can('users.edit');
    }
}
