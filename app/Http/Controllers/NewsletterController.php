<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::query()->firstOrCreate(
            ['email' => $validated['email']],
            [
                'source' => 'blog',
                'ip_address' => $request->ip(),
            ]
        );

        return back(303)->with('newsletter_success', "You're subscribed! Watch your inbox for our next Local SEO tips.");
    }
}
