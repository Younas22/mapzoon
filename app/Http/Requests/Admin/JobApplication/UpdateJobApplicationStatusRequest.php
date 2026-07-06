<?php

namespace App\Http\Requests\Admin\JobApplication;

use App\Models\JobApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('job_application'));
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(JobApplication::STATUSES))],
        ];
    }
}
