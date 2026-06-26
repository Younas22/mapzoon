<?php

namespace App\Models\Concerns;

trait FormatsFileSize
{
    public function sizeForHumans(): string
    {
        $bytes = $this->size;

        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, 1).' '.$unit;
            }

            $bytes /= 1024;
        }

        return round($bytes, 1).' TB';
    }
}
