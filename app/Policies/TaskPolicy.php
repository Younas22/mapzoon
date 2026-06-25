<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('tasks.view');
    }

    /**
     * A user can view a task they manage, but also one assigned to them
     * or created by them — so individual contributors can see their own work
     * without needing the blanket tasks.view permission.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.view')
            || $task->assigned_to === $user->id
            || $task->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('tasks.create');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.edit');
    }

    /**
     * Narrower than update() — the assignee can move their own task along
     * (status/progress) without holding full edit rights.
     */
    public function updateProgress(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.edit') || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermission('tasks.delete');
    }
}
