<?php

namespace App\Http\Requests\Admin\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('project'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'project_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(array_keys(Project::STATUSES))],
            'priority' => ['required', Rule::in(array_keys(Project::PRIORITIES))],
            'services_included' => ['array'],
            'services_included.*' => ['string', 'max:255'],
        ];
    }
}
