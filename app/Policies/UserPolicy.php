<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('users.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('users.edit');
    }

    public function delete(User $user, User $model): bool
    {
        if (! $user->hasPermission('users.delete')) {
            return false;
        }

        if ($user->is($model)) {
            return false;
        }

        if ($model->isSuperAdmin() && User::query()->whereRelation('role', 'slug', Role::SUPER_ADMIN)->count() <= 1) {
            return false;
        }

        return true;
    }
}
