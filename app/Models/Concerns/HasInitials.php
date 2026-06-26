<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasInitials
{
    public function initials(): string
    {
        $source = $this->{$this->initialsSourceAttribute()} ?? '';

        $initials = Str::of($source)
            ->explode(' ')
            ->filter()
            ->map(fn ($part) => Str::substr($part, 0, 1))
            ->take(2)
            ->join('');

        return Str::upper($initials);
    }

    protected function initialsSourceAttribute(): string
    {
        return 'name';
    }
}
