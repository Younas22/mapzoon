<?php

namespace App\Policies;

use App\Models\CaseStudy;
use App\Models\User;

class CaseStudyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('case-studies.view');
    }

    public function view(User $user, CaseStudy $caseStudy): bool
    {
        return $user->hasPermission('case-studies.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('case-studies.create');
    }

    public function update(User $user, CaseStudy $caseStudy): bool
    {
        return $user->hasPermission('case-studies.edit');
    }

    public function delete(User $user, CaseStudy $caseStudy): bool
    {
        return $user->hasPermission('case-studies.delete');
    }
}
