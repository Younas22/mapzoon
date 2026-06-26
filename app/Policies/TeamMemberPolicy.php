<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('teams.view');
    }

    public function view(User $user, TeamMember $teamMember): bool
    {
        return $user->hasPermission('teams.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('teams.create');
    }

    public function update(User $user, TeamMember $teamMember): bool
    {
        return $user->hasPermission('teams.edit');
    }

    public function delete(User $user, TeamMember $teamMember): bool
    {
        return $user->hasPermission('teams.delete');
    }
}
