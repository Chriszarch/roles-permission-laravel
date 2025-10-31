<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function view(User $authUser, Role $role): bool
    {
        return $authUser->can('roles.view');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('roles.create');
    }

    public function update(User $authUser, Role $role): bool
    {
        return $authUser->can('roles.edit');
    }

    public function delete(User $authUser, Role $role): bool
    {
        return $authUser->can('roles.delete');
    }
}
