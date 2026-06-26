<?php

namespace App\Http\Requests\Admin\Project;

use App\Models\ProjectCredential;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectCredentialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('credential'));
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', Rule::in(array_keys(ProjectCredential::PLATFORMS))],
            'label' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            // Blank means "leave the current password unchanged" — see ProjectCredentialController::update().
            'password' => ['nullable', 'string', 'max:255'],
            'recovery_email' => ['nullable', 'email', 'max:255'],
            'recovery_phone' => ['nullable', 'string', 'max:30'],
            'url' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
