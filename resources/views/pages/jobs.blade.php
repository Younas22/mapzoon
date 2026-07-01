@extends('layouts.app')

@section('title', 'Careers — Join the MAPZOON Team | Local SEO Jobs Pakistan')
@section('description', 'We are actively hiring at MAPZOON. Apply for Local SEO, WordPress Development, Sales, and Digital Marketing roles in Lahore, Pakistan.')

@section('content')
    @include('sections.navbar')

    <div class="pt-20">

        {{-- Hero --}}
        <section class="relative overflow-hidden bg-black py-20 lg:py-28">
            <div class="absolute inset-0 -z-10" aria-hidden="true">
                <div class="absolute -left-40 -top-40 h-[600px] w-[600px] rounded-full bg-primary-600/20 blur-3xl"></div>
                <div class="absolute -bottom-40 right-0 h-[500px] w-[500px] rounded-full bg-primary-500/10 blur-3xl"></div>
            </div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="inline-flex items-center gap-2 rounded-full bg-primary-500/20 px-4 py-1.5 text-sm font-semibold text-primary-400 ring-1 ring-primary-500/30">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-primary-500"></span>
                        </span>
                        We're Actively Hiring
                    </span>
                    <h1 class="mt-5 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Build Your Career in<br>
                        <span class="text-primary-400">Local SEO & Digital Growth</span>
                    </h1>
                    <p class="mt-6 text-base leading-relaxed text-slate-400 sm:text-lg">
                        MAPZOON is a fast-growing Local SEO agency helping businesses dominate Google Maps across Pakistan.
                        We're looking for passionate individuals to join our team and grow with us.
                    </p>

                    {{-- Perks --}}
                    <div class="mt-10 flex flex-wrap justify-center gap-3">
                        @foreach ([
                            ['icon' => 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z', 'label' => 'Lahore, Pakistan'],
                            ['icon' => 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6', 'label' => 'Market-Competitive Salary'],
                            ['icon' => 'M13 7h8m0 0v8m0-8-8 8-4-4-6 6', 'label' => 'Fast Career Growth'],
                            ['icon' => 'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z', 'label' => 'Hybrid Work Option'],
                            ['icon' => 'M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z', 'label' => 'Training & Development'],
                        ] as $perk)
                            <span class="flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-slate-300">
                                <svg class="h-4 w-4 flex-none text-primary-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $perk['icon'] }}" />
                                </svg>
                                {{ $perk['label'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Open Positions --}}
        <section class="bg-white py-14">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="text-center text-2xl font-extrabold text-slate-900">Open Positions</h2>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    @foreach ([
                        'Google Maps SEO Specialist',
                        'Local SEO Executive',
                        'WordPress Developer',
                        'Content Writer (SEO)',
                        'Digital Marketing Executive',
                        'Sales Executive',
                        'Customer Support',
                    ] as $pos)
                        <span class="rounded-full border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700">{{ $pos }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Application Form --}}
        <section class="bg-slate-50 py-16 lg:py-24" id="apply">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">

                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Submit Your Application</h2>
                    <p class="mt-2 text-sm text-slate-500">Complete the form below — we review every application carefully</p>
                </div>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="mb-8 flex items-start gap-4 rounded-2xl border border-primary-200 bg-primary-50 p-5">
                        <svg class="mt-0.5 h-6 w-6 flex-none text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
                        </svg>
                        <p class="text-sm font-semibold text-primary-800">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('jobs.apply') }}" method="POST" enctype="multipart/form-data" class="space-y-8 rounded-3xl border border-slate-200 bg-white p-8 shadow-xl lg:p-10">
                    @csrf

                    {{-- Personal Information --}}
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-500 text-xs font-bold text-white">1</span>
                            Personal Information
                        </legend>

                        {{-- Profile Photo --}}
                        <div class="mb-5">
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Profile Photo <span class="font-normal text-slate-400">(optional)</span></label>
                            <div class="flex items-center gap-4">
                                <div id="photo-preview" class="flex h-16 w-16 flex-none items-center justify-center rounded-full bg-slate-100 text-slate-400 overflow-hidden">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                </div>
                                <label class="cursor-pointer rounded-xl border border-dashed border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:border-primary-400 hover:text-primary-600">
                                    Upload Photo
                                    <input type="file" name="photo" accept="image/jpeg,image/png" class="sr-only" id="photo-input">
                                </label>
                                <span class="text-xs text-slate-400">JPG, PNG — max 4MB</span>
                            </div>
                            @error('photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="mb-1.5 block text-sm font-semibold text-slate-700">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Muhammad"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('first_name') border-red-400 @enderror">
                                @error('first_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="last_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Ali"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('last_name') border-red-400 @enderror">
                                @error('last_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="gender" class="mb-1.5 block text-sm font-semibold text-slate-700">Gender</label>
                            <select id="gender" name="gender" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Contact Information --}}
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-500 text-xs font-bold text-white">2</span>
                            Contact Information
                        </legend>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="yourname@email.com"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('email') border-red-400 @enderror">
                                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-semibold text-slate-700">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+92 300 1234567"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('phone') border-red-400 @enderror">
                                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="whatsapp" class="mb-1.5 block text-sm font-semibold text-slate-700">WhatsApp Number <span class="font-normal text-slate-400">(optional)</span></label>
                                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="+92 300 1234567"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                <p class="mt-1 text-xs text-slate-400">Leave blank if same as phone</p>
                            </div>
                            <div>
                                <label for="city" class="mb-1.5 block text-sm font-semibold text-slate-700">City / Location</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="e.g. Lahore, Karachi"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                            </div>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Position & Background --}}
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-500 text-xs font-bold text-white">3</span>
                            Position &amp; Professional Background
                        </legend>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="position" class="mb-1.5 block text-sm font-semibold text-slate-700">Position Applying For <span class="text-red-500">*</span></label>
                                <select id="position" name="position" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('position') border-red-400 @enderror">
                                    <option value="">Select a Position</option>
                                    @foreach ([
                                        'Google Maps SEO Specialist',
                                        'Local SEO Executive',
                                        'WordPress Developer',
                                        'Content Writer (SEO)',
                                        'Digital Marketing Executive',
                                        'Sales Executive',
                                        'Customer Support',
                                    ] as $pos)
                                        <option value="{{ $pos }}" {{ old('position') === $pos ? 'selected' : '' }}>{{ $pos }}</option>
                                    @endforeach
                                </select>
                                @error('position')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="experience_level" class="mb-1.5 block text-sm font-semibold text-slate-700">Experience Level</label>
                                <select id="experience_level" name="experience_level" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                    <option value="">Select Experience</option>
                                    <option value="fresher" {{ old('experience_level') === 'fresher' ? 'selected' : '' }}>Fresher (0 years)</option>
                                    <option value="0-1" {{ old('experience_level') === '0-1' ? 'selected' : '' }}>Less than 1 year</option>
                                    <option value="1-2" {{ old('experience_level') === '1-2' ? 'selected' : '' }}>1–2 years</option>
                                    <option value="2-4" {{ old('experience_level') === '2-4' ? 'selected' : '' }}>2–4 years</option>
                                    <option value="4-6" {{ old('experience_level') === '4-6' ? 'selected' : '' }}>4–6 years</option>
                                    <option value="6+" {{ old('experience_level') === '6+' ? 'selected' : '' }}>6+ years</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="education_level" class="mb-1.5 block text-sm font-semibold text-slate-700">Education Level</label>
                                <select id="education_level" name="education_level" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                    <option value="">Select Education</option>
                                    <option value="matric" {{ old('education_level') === 'matric' ? 'selected' : '' }}>Matric</option>
                                    <option value="intermediate" {{ old('education_level') === 'intermediate' ? 'selected' : '' }}>Intermediate / FA / FSc</option>
                                    <option value="bachelors" {{ old('education_level') === 'bachelors' ? 'selected' : '' }}>Bachelor's (BS/BA/BCS)</option>
                                    <option value="masters" {{ old('education_level') === 'masters' ? 'selected' : '' }}>Master's (MS/MBA)</option>
                                    <option value="other" {{ old('education_level') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label for="availability" class="mb-1.5 block text-sm font-semibold text-slate-700">When Can You Start?</label>
                                <select id="availability" name="availability" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                    <option value="">Select Availability</option>
                                    <option value="immediately" {{ old('availability') === 'immediately' ? 'selected' : '' }}>Immediately</option>
                                    <option value="1-week" {{ old('availability') === '1-week' ? 'selected' : '' }}>Within 1 week</option>
                                    <option value="2-weeks" {{ old('availability') === '2-weeks' ? 'selected' : '' }}>Within 2 weeks</option>
                                    <option value="1-month" {{ old('availability') === '1-month' ? 'selected' : '' }}>Within 1 month</option>
                                    <option value="negotiable" {{ old('availability') === 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="salary_expectation" class="mb-1.5 block text-sm font-semibold text-slate-700">Expected Monthly Salary (PKR)</label>
                            <select id="salary_expectation" name="salary_expectation" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                <option value="">Select Salary Range</option>
                                <option value="20k-35k" {{ old('salary_expectation') === '20k-35k' ? 'selected' : '' }}>PKR 20,000 – 35,000</option>
                                <option value="35k-50k" {{ old('salary_expectation') === '35k-50k' ? 'selected' : '' }}>PKR 35,000 – 50,000</option>
                                <option value="50k-75k" {{ old('salary_expectation') === '50k-75k' ? 'selected' : '' }}>PKR 50,000 – 75,000</option>
                                <option value="75k-100k" {{ old('salary_expectation') === '75k-100k' ? 'selected' : '' }}>PKR 75,000 – 100,000</option>
                                <option value="100k+" {{ old('salary_expectation') === '100k+' ? 'selected' : '' }}>PKR 100,000+</option>
                                <option value="negotiable" {{ old('salary_expectation') === 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                            </select>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Online Profiles --}}
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-500 text-xs font-bold text-white">4</span>
                            Online Profiles
                            <span class="text-sm font-normal text-slate-400">(optional)</span>
                        </legend>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="linkedin_url" class="mb-1.5 block text-sm font-semibold text-slate-700">LinkedIn Profile</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/username"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('linkedin_url') border-red-400 @enderror">
                                @error('linkedin_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="portfolio_url" class="mb-1.5 block text-sm font-semibold text-slate-700">Portfolio / GitHub</label>
                                <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://github.com/username"
                                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('portfolio_url') border-red-400 @enderror">
                                @error('portfolio_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- CV Upload --}}
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-500 text-xs font-bold text-white">5</span>
                            Resume / CV
                        </legend>

                        <label class="group block cursor-pointer rounded-2xl border-2 border-dashed border-slate-300 p-8 text-center transition hover:border-primary-400 hover:bg-primary-50/40" id="cv-drop-zone">
                            <svg class="mx-auto h-10 w-10 text-slate-400 group-hover:text-primary-400 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                            <p class="mt-3 text-sm font-semibold text-slate-700" id="cv-label">Click to upload or drag &amp; drop your CV</p>
                            <p class="mt-1 text-xs text-slate-400">PDF, JPG, PNG — max 10MB</p>
                            <input type="file" name="cv" accept=".pdf,image/jpeg,image/png" class="sr-only" id="cv-input">
                        </label>
                        @error('cv')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Cover Letter --}}
                    <fieldset>
                        <legend class="mb-5 flex items-center gap-2 text-base font-bold text-slate-900">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-500 text-xs font-bold text-white">6</span>
                            Cover Letter / Message
                            <span class="text-sm font-normal text-slate-400">(optional)</span>
                        </legend>
                        <textarea name="cover_letter" id="cover_letter" rows="5" placeholder="Tell us about yourself — your skills, experience, why you want to join MAPZOON, and what value you'll bring to the team..."
                                  class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 placeholder-slate-400 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 resize-none">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- CAPTCHA --}}
                    @php
                        $num1 = session('captcha_a', rand(1, 9));
                        $num2 = session('captcha_b', rand(1, 9));
                        $sum  = $num1 + $num2;
                        session(['captcha_a' => $num1, 'captcha_b' => $num2]);
                    @endphp
                    <fieldset>
                        <legend class="mb-4 text-sm font-bold text-slate-900">Security Check <span class="text-red-500">*</span></legend>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-800">{{ $num1 }} + {{ $num2 }} = ?</span>
                            <input type="number" name="captcha_answer" placeholder="?"
                                   class="w-24 rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-bold text-slate-900 transition focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 @error('captcha_answer') border-red-400 @enderror">
                            <input type="hidden" name="captcha_sum" value="{{ $sum }}">
                            <span class="text-sm text-slate-500">Solve the simple math question above</span>
                        </div>
                        @error('captcha_answer')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full rounded-2xl bg-primary-500 px-6 py-4 text-base font-bold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/40 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        Submit My Application
                    </button>

                    <p class="text-center text-xs text-slate-400">
                        <svg class="mr-1 inline h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Your information is kept confidential and will only be used for recruitment purposes.
                    </p>
                </form>
            </div>
        </section>

        {{-- Why Join Us --}}
        <section class="bg-white py-16 lg:py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">Why Join MAPZOON</h2>
                    <p class="mt-3 text-slate-500">A place where careers take flight</p>
                </div>

                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ([
                        ['icon' => 'M13 7h8m0 0v8m0-8-8 8-4-4-6 6', 'title' => 'Accelerated Growth', 'body' => 'Work on real, impactful projects from day one. We promote from within and reward performance with fast career advancement.'],
                        ['icon' => 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z', 'title' => 'Local Impact', 'body' => 'Be part of a platform helping hundreds of local businesses in Pakistan rank higher and get more customers every day.'],
                        ['icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75', 'title' => 'Collaborative Culture', 'body' => 'A supportive, inclusive workplace where your ideas matter and every team member is valued.'],
                        ['icon' => 'M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z', 'title' => 'Learning & Training', 'body' => 'Regular workshops, skill development sessions, and access to premium learning resources — all company-sponsored.'],
                        ['icon' => 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6', 'title' => 'Competitive Package', 'body' => 'Market-rate salaries, performance bonuses, Eid allowances, and annual increments based on merit.'],
                        ['icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z', 'title' => 'Work-Life Balance', 'body' => 'Flexible hours, hybrid options for eligible roles, and a respectful management culture that values your time.'],
                    ] as $perk)
                        <div class="reveal rounded-2xl border border-slate-100 bg-slate-50 p-6 transition hover:shadow-md">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-500/10">
                                <svg class="h-5 w-5 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $perk['icon'] }}" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-bold text-slate-900">{{ $perk['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $perk['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>

    @include('partials.landing.footer')
    @include('partials.landing.quote-modal')

    <script>
        // Photo preview
        document.getElementById('photo-input').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('photo-preview');
                preview.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-cover">`;
            };
            reader.readAsDataURL(file);
        });

        // CV upload label
        document.getElementById('cv-input').addEventListener('change', function () {
            const label = document.getElementById('cv-label');
            label.textContent = this.files[0] ? this.files[0].name : 'Click to upload or drag & drop your CV';
        });
    </script>
@endsection
