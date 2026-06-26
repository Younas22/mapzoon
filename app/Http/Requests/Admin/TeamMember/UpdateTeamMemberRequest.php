<?php

namespace App\Http\Requests\Admin\TeamMember;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('team_member'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'email' => ['nullable', 'email', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(array_keys(TeamMember::STATUSES))],
            'is_visible_on_homepage' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
