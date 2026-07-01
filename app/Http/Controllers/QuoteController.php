<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class QuoteController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'first_name'   => ['required', 'string', 'max:100'],
            'last_name'    => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:255'],
            'phone'        => ['required', 'string', 'max:30'],
            'designation'  => ['nullable', 'string', 'max:100'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:50'],
            'service'      => ['nullable', 'string', 'max:255'],
            'captcha'      => ['required', 'integer'],
        ], [
            'captcha.required' => 'Please answer the verification question.',
            'captcha.integer'  => 'Please enter a valid number for the verification.',
        ]);

        if ((int) $request->captcha !== 10) {
            throw ValidationException::withMessages([
                'captcha' => 'Incorrect answer. Hint: 7 + 3 = ?',
            ]);
        }

        $name    = trim($request->first_name . ' ' . $request->last_name);
        $message = implode("\n", array_filter([
            $request->designation  ? "Designation: {$request->designation}"   : null,
            $request->company_size ? "Company Size: {$request->company_size}" : null,
        ]));

        Lead::query()->create([
            'name'          => $name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'business_name' => $request->company_name,
            'service'       => $request->service,
            'message'       => $message ?: null,
            'status'        => 'new',
            'source'        => 'quote_form',
        ]);

        Log::info('New MAPZOON quote request', ['name' => $name, 'email' => $request->email, 'service' => $request->service]);

        return response()->json(['success' => true, 'message' => "Thank you, {$request->first_name}! We'll get back to you within 24 hours."]);
    }
}
