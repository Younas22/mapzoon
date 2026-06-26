<?php

namespace App\Http\Requests\Admin\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('client'));
    }

    public function rules(): array
    {
        return [
            'user_ids' => ['array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
