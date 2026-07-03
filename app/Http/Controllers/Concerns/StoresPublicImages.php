<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait StoresPublicImages
{
    /**
     * Moves an uploaded image into public/uploads/{folder} and returns its relative path.
     */
    protected function storePublicImage(UploadedFile $file, string $folder): string
    {
        $directory = "uploads/{$folder}";

        if (! is_dir(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        $filename = Str::random(40).'.'.$file->getClientOriginalExtension();

        $file->move(public_path($directory), $filename);

        return "{$directory}/{$filename}";
    }

    protected function deletePublicImage(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }
}
