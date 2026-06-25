<?php

namespace App\Http\Requests\Admin\Task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateProgress', $this->route('task'));
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(Task::STATUSES))],
        ];
    }
}
