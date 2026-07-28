<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    public const STATUSES = [
        'new' => 'New',
        'reviewed' => 'Reviewed',
        'shortlisted' => 'Shortlisted',
        'rejected' => 'Rejected',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'gender',
        'email', 'phone', 'whatsapp', 'city',
        'position', 'experience_level', 'education_level',
        'availability', 'salary_expectation',
        'linkedin_url', 'portfolio_url',
        'photo_path', 'cv_path', 'cover_letter', 'status',
    ];

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function photoUrl(): ?string
    {
        return $this->photo_path ? asset($this->photo_path) : null;
    }

    public function cvUrl(): ?string
    {
        return $this->cv_path ? asset($this->cv_path) : null;
    }
}
