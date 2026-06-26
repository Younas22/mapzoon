<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VideoReview;

class VideoReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('reviews.view');
    }

    public function view(User $user, VideoReview $videoReview): bool
    {
        return $user->hasPermission('reviews.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('reviews.create');
    }

    public function update(User $user, VideoReview $videoReview): bool
    {
        return $user->hasPermission('reviews.edit');
    }

    public function delete(User $user, VideoReview $videoReview): bool
    {
        return $user->hasPermission('reviews.delete');
    }
}
