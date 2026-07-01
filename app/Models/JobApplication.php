<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'gender',
        'email', 'phone', 'whatsapp', 'city',
        'position', 'experience_level', 'education_level',
        'availability', 'salary_expectation',
        'linkedin_url', 'portfolio_url',
        'photo_path', 'cv_path', 'cover_letter', 'status',
    ];
}
