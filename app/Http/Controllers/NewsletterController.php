<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        Log::info('New MAPZOON newsletter subscription', $validated);

        return back(303)->with('newsletter_success', "You're subscribed! Watch your inbox for our next Local SEO tips.");
    }
}
