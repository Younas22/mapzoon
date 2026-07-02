@php
    $existingScreenshotsForJs = collect($caseStudy->screenshots ?? [])
        ->map(fn ($path) => ['path' => $path, 'url' => asset($path)])
        ->values()
        ->all();
@endphp

<div
    x-data="caseStudyForm(@js([
        'imagePreview' => $caseStudy->imageUrl(),
        'slugTouched' => (bool) $caseStudy->id,
        'existingScreenshots' => $existingScreenshotsForJs,
    ]))"
    class="mx-auto max-w-4xl"
>
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        {{-- Basic Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Basic Information</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Title</label>
                    <input type="text" name="title" value="{{ old('title', $caseStudy->title) }}" @input="onTitleInput($event)"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('title')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $caseStudy->slug) }}" x-ref="slugInput" @input="onSlugInput()"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('slug')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('description', $caseStudy->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Main Image</label>
                    <div class="flex items-center gap-4">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="h-20 w-32 rounded-lg object-cover" alt="Case study image preview">
                        </template>
                        <template x-if="!imagePreview">
                            <span class="flex h-20 w-32 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-400">No image</span>
                        </template>
                        <input type="file" name="image" accept="image/*" @change="onImageChange($event)" class="text-sm text-slate-600">
                    </div>
                    @error('image')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Display Order</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $caseStudy->display_order ?? 0) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('display_order')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $caseStudy->is_active ?? true))
                               class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        Active (visible on the website)
                    </label>
                </div>
            </div>
        </div>

        {{-- Links --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Links</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Google Business Profile Link</label>
                    <input type="text" name="gmb_link" value="{{ old('gmb_link', $caseStudy->gmb_link) }}" placeholder="https://g.page/..."
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('gmb_link')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Website Link</label>
                    <input type="text" name="website_link" value="{{ old('website_link', $caseStudy->website_link) }}" placeholder="https://..."
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('website_link')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Video URL (YouTube)</label>
                    <input type="text" name="video_url" value="{{ old('video_url', $caseStudy->video_url) }}" placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('video_url')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Business Owner --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-semibold text-ink">Business Owner</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Owner Name</label>
                    <input type="text" name="owner_name" value="{{ old('owner_name', $caseStudy->owner_name) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                    @error('owner_name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Owner Review</label>
                    <textarea name="owner_review" rows="3"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('owner_review', $caseStudy->owner_review) }}</textarea>
                    @error('owner_review')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Screenshots --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-1 text-base font-semibold text-ink">Screenshots</h2>
            <p class="mb-4 text-sm text-slate-500">Results screenshots shown in the case study gallery.</p>

            <div class="mb-4 flex flex-wrap gap-3">
                <template x-for="(screenshot, index) in existingScreenshots" :key="screenshot.path">
                    <div class="relative">
                        <img :src="screenshot.url" class="h-20 w-28 rounded-lg border border-slate-200 object-cover" alt="Screenshot">
                        <input type="hidden" :name="'existing_screenshots[]'" :value="screenshot.path">
                        <button type="button" @click="removeExistingScreenshot(index)"
                                class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white shadow hover:bg-rose-700">
                            &times;
                        </button>
                    </div>
                </template>

                <template x-for="(preview, index) in newScreenshotPreviews" :key="index">
                    <div class="relative">
                        <img :src="preview" class="h-20 w-28 rounded-lg border border-primary-200 object-cover" alt="New screenshot preview">
                        <span class="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-primary-500 text-[10px] font-bold text-white shadow">New</span>
                    </div>
                </template>
            </div>

            <input type="file" name="new_screenshots[]" accept="image/*" multiple @change="onScreenshotsChange($event)" class="text-sm text-slate-600">
            @error('new_screenshots.*')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-3">
            @if ($caseStudy->id && $caseStudy->is_active)
                <a href="{{ route('case-studies.show', $caseStudy->slug) }}" target="_blank"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    View Live
                </a>
            @endif
            <a href="{{ route('admin.case-studies.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>
