<?php

namespace App\Policies;

use App\Models\ProjectCredential;
use App\Models\User;

class ProjectCredentialPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('credentials.view');
    }

    public function view(User $user, ProjectCredential $credential): bool
    {
        return $user->hasPermission('credentials.view');
    }

    /**
     * Separate from view() — knowing a credential exists is not the same as
     * being trusted to see its plaintext password.
     */
    public function reveal(User $user, ProjectCredential $credential): bool
    {
        return $user->hasPermission('credentials.reveal');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('credentials.create');
    }

    public function update(User $user, ProjectCredential $credential): bool
    {
        return $user->hasPermission('credentials.edit');
    }

    public function delete(User $user, ProjectCredential $credential): bool
    {
        return $user->hasPermission('credentials.delete');
    }
}
