<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'company_name',
        'tagline',
        'footer_description',
        'working_hours',
        'phone',
        'email',
        'address',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'logo_dark_path',
        'logo_light_path',
        'favicon_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'google_analytics_code',
        'google_search_console_tag',
    ];

    /**
     * The site only ever has one settings row; fetch it (creating it on
     * first access) instead of treating this as a normal resource.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }

    public function logoDarkUrl(): ?string
    {
        return $this->logo_dark_path ? asset($this->logo_dark_path) : asset('logo.png');
    }

    public function logoLightUrl(): ?string
    {
        return $this->logo_light_path ? asset($this->logo_light_path) : asset('logo.png');
    }

    public function faviconUrl(): ?string
    {
        return $this->favicon_path ? asset($this->favicon_path) : asset('favicon.ico');
    }
}
