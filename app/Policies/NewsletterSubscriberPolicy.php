<?php

namespace App\Policies;

use App\Models\NewsletterSubscriber;
use App\Models\User;

class NewsletterSubscriberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('newsletter.view');
    }

    public function view(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->hasPermission('newsletter.view');
    }

    public function delete(User $user, NewsletterSubscriber $newsletterSubscriber): bool
    {
        return $user->hasPermission('newsletter.delete');
    }
}
