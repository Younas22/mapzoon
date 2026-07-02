<?php

namespace App\Http\Controllers;

use App\Mail\ContactReceivedMail;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $lead = Lead::query()->create([
            ...$validated,
            'status' => 'new',
            'source' => 'website_contact',
        ]);

        try {
            Mail::to($lead->email)->send(new ContactReceivedMail($lead));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact autoresponder email', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
        }

        return redirect('/#contact', 303)->with('success', "Thanks {$validated['name']}! We've received your request and will reach out within 24 hours.");
    }
}
