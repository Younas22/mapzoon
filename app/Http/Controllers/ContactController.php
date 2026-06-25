<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        Log::info('New MAPZOON consultation request', $validated);

        Lead::query()->create([
            ...$validated,
            'status' => 'new',
            'source' => 'website_contact',
        ]);

        return redirect('/#contact', 303)->with('success', "Thanks {$validated['name']}! We've received your request and will reach out within 24 hours.");
    }
}
