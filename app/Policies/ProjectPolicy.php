<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('projects.view');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->hasPermission('projects.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('projects.create');
    }

    /**
     * Also gates project credentials, internal notes, and the activity log in
     * the UI — these are kept out of reach of view-only roles (e.g. Client).
     */
    public function update(User $user, Project $project): bool
    {
        return $user->hasPermission('projects.edit');
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasPermission('projects.delete');
    }
}
