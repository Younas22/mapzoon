@php
    $tabs = [
        'branding' => 'Branding',
        'footer' => 'Footer & Contact',
        'seo' => 'SEO',
        'analytics' => 'Analytics & Search Console',
    ];
@endphp

<x-admin-layout title="Site Settings">
    <div
        x-data="settingsForm(@js([
            'logoDarkUrl' => $setting->logoDarkUrl(),
            'logoLightUrl' => $setting->logoLightUrl(),
            'faviconUrl' => $setting->faviconUrl(),
        ]))"
        x-cloak
        class="mx-auto max-w-5xl space-y-6"
    >
        {{-- Tabs --}}
        <div class="flex flex-wrap gap-2 border-b border-slate-200">
            @foreach ($tabs as $key => $label)
                <button
                    type="button"
                    @click="activeTab = '{{ $key }}'"
                    :class="activeTab === '{{ $key }}' ? 'border-primary-600 text-primary-700' : 'border-transparent text-slate-500 hover:text-ink'"
                    class="-mb-px border-b-2 px-3 py-2 text-sm font-medium"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Branding --}}
            <div x-show="activeTab === 'branding'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-1 text-base font-semibold text-ink">Branding</h2>
                <p class="mb-4 text-sm text-slate-500">Dark logo shows on light backgrounds (header). Light logo shows on dark backgrounds (footer).</p>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Dark Logo (header)</label>
                        <div class="mb-2 flex h-16 items-center rounded-lg bg-slate-100 p-2">
                            <img :src="logoDarkPreview" class="h-full w-auto object-contain" alt="Dark logo preview">
                        </div>
                        <input type="file" name="logo_dark" accept="image/*" @change="onImageChange($event, 'logoDarkPreview')" class="text-sm text-slate-600">
                        @error('logo_dark')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Light Logo (footer)</label>
                        <div class="mb-2 flex h-16 items-center rounded-lg bg-slate-900 p-2">
                            <img :src="logoLightPreview" class="h-full w-auto object-contain" alt="Light logo preview">
                        </div>
                        <input type="file" name="logo_light" accept="image/*" @change="onImageChange($event, 'logoLightPreview')" class="text-sm text-slate-600">
                        @error('logo_light')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Favicon</label>
                        <div class="mb-2 flex h-16 items-center rounded-lg bg-slate-100 p-2">
                            <img :src="faviconPreview" class="h-10 w-10 object-contain" alt="Favicon preview">
                        </div>
                        <input type="file" name="favicon" accept=".ico,.png,.svg" @change="onImageChange($event, 'faviconPreview')" class="text-sm text-slate-600">
                        @error('favicon')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Footer & Contact --}}
            <div x-show="activeTab === 'footer'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-4 text-base font-semibold text-ink">Footer &amp; Contact</h2>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $setting->company_name) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('company_name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $setting->tagline) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('tagline')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Footer Description</label>
                        <textarea name="footer_description" rows="2"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('footer_description', $setting->footer_description) }}</textarea>
                        @error('footer_description')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Working Hours</label>
                        <textarea name="working_hours" rows="3" placeholder="Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 10:00 AM - 2:00 PM&#10;Sunday: Closed"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('working_hours', $setting->working_hours) }}</textarea>
                        @error('working_hours')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('phone')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $setting->email) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                        <input type="text" name="address" value="{{ old('address', $setting->address) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('address')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Facebook URL</label>
                        <input type="text" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}" placeholder="https://facebook.com/..."
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('facebook_url')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Twitter / X URL</label>
                        <input type="text" name="twitter_url" value="{{ old('twitter_url', $setting->twitter_url) }}" placeholder="https://x.com/..."
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('twitter_url')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Instagram URL</label>
                        <input type="text" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}" placeholder="https://instagram.com/..."
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('instagram_url')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">LinkedIn URL</label>
                        <input type="text" name="linkedin_url" value="{{ old('linkedin_url', $setting->linkedin_url) }}" placeholder="https://linkedin.com/..."
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('linkedin_url')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">YouTube URL</label>
                        <input type="text" name="youtube_url" value="{{ old('youtube_url', $setting->youtube_url) }}" placeholder="https://youtube.com/..."
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('youtube_url')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div x-show="activeTab === 'seo'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-1 text-base font-semibold text-ink">SEO</h2>
                <p class="mb-4 text-sm text-slate-500">Default meta tags used site-wide when a page doesn't set its own.</p>

                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">
                        @error('meta_title')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Meta Description</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('meta_description', $setting->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Meta Keywords</label>
                        <textarea name="meta_keywords" rows="2" placeholder="Comma-separated keywords"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('meta_keywords', $setting->meta_keywords) }}</textarea>
                        @error('meta_keywords')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Analytics & Search Console --}}
            <div x-show="activeTab === 'analytics'" class="rounded-2xl border border-slate-200 bg-white p-6">
                <h2 class="mb-1 text-base font-semibold text-ink">Analytics &amp; Search Console</h2>
                <p class="mb-4 text-sm text-slate-500">Paste the complete tag/snippet Google gives you — it's injected as-is into every page's &lt;head&gt;.</p>

                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Google Analytics Tag</label>
                        <textarea name="google_analytics_code" rows="6" placeholder="<script async src=&quot;https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX&quot;></script>&#10;<script>...</script>"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('google_analytics_code', $setting->google_analytics_code) }}</textarea>
                        @error('google_analytics_code')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Google Search Console Verification Tag</label>
                        <textarea name="google_search_console_tag" rows="3" placeholder='<meta name="google-site-verification" content="..." />'
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-primary-500 focus:outline focus:outline-2 focus:outline-primary-200">{{ old('google_search_console_tag', $setting->google_search_console_tag) }}</textarea>
                        @error('google_search_console_tag')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
