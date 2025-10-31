<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function view(User $authUser, Permission $permission): bool
    {
        return $authUser->can('permissions.view');
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('permissions.create');
    }

    public function update(User $authUser, Permission $permission): bool
    {
        return $authUser->can('permissions.edit');
    }

    public function delete(User $authUser, Permission $permission): bool
    {
        return $authUser->can('permissions.delete');
    }
}
