<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = SiteSetting::current();

        $this->authorize('viewAny', $setting);

        return view('admin.settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        $setting = SiteSetting::current();

        $setting->fill($request->safe()->except(['logo_dark', 'logo_light', 'favicon']));

        foreach (['logo_dark', 'logo_light', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                $column = "{$field}_path";
                $this->deletePublicImage($setting->{$column});
                $setting->{$column} = $this->storePublicImage($request->file($field), 'settings');
            }
        }

        $setting->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }

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
