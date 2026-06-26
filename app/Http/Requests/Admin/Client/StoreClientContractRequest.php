<?php

namespace App\Http\Requests\Admin\Client;

use App\Models\ClientContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('client'));
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys(ClientContract::STATUSES))],
            'notes' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
