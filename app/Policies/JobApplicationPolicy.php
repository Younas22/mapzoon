<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('job-applications.view');
    }

    public function view(User $user, JobApplication $jobApplication): bool
    {
        return $user->hasPermission('job-applications.view');
    }

    public function update(User $user, JobApplication $jobApplication): bool
    {
        return $user->hasPermission('job-applications.edit');
    }

    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return $user->hasPermission('job-applications.delete');
    }
}
