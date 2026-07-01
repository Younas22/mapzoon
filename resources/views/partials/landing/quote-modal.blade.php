{{-- Get Free Quote Modal --}}
<div id="quote-modal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="quote-modal-title">
    {{-- Backdrop --}}
    <div id="quote-modal-backdrop" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

    {{-- Modal card --}}
    <div class="relative w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl">

        {{-- Green top bar --}}
        <div class="bg-primary-500 px-8 py-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 id="quote-modal-title" class="text-xl font-extrabold uppercase tracking-wide text-white sm:text-2xl">
                        Get a Free Quote
                    </h2>
                    <p class="mt-1.5 max-w-lg text-sm leading-relaxed text-primary-100">
                        If you have any requirement please share with us at
                        <a href="mailto:info@mapzoon.com" class="font-semibold underline underline-offset-2 hover:text-white">info@mapzoon.com</a>
                        or simply send us your inquiry by filling out the form below.
                    </p>
                </div>
                <button type="button" id="quote-modal-close" class="ml-4 flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-white/20 text-white transition hover:bg-white/30" aria-label="Close">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Form body --}}
        <div class="max-h-[70vh] overflow-y-auto px-8 py-7">

            {{-- Success message (hidden by default) --}}
            <div id="quote-success" class="mb-5 hidden rounded-2xl border border-primary-200 bg-primary-50 px-5 py-4 text-sm font-medium text-primary-700"></div>

            {{-- Error message (hidden by default) --}}
            <div id="quote-error" class="mb-5 hidden rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700"></div>

            <form id="quote-form" novalidate>
                @csrf

                {{-- Service (hidden, set from hero dropdown) --}}
                <input type="hidden" name="service" id="quote-service-field">

                {{-- Selected service badge --}}
                <div id="quote-service-badge" class="mb-5 hidden">
                    <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-4 py-1.5 text-sm font-semibold text-primary-700">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="quote-service-label">Service Selected</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- First Name --}}
                    <div>
                        <label for="q-first-name" class="block text-sm font-semibold text-slate-700">First Name <span class="text-red-500">*</span></label>
                        <input type="text" id="q-first-name" name="first_name" placeholder="Enter Name" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="first_name"></p>
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label for="q-last-name" class="block text-sm font-semibold text-slate-700">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" id="q-last-name" name="last_name" placeholder="Enter Last Name" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="last_name"></p>
                    </div>

                    {{-- Work Email --}}
                    <div>
                        <label for="q-email" class="block text-sm font-semibold text-slate-700">Work Email <span class="text-red-500">*</span></label>
                        <input type="email" id="q-email" name="email" placeholder="Enter Email" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="email"></p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="q-phone" class="block text-sm font-semibold text-slate-700">Phone <span class="text-red-500">*</span></label>
                        <div class="mt-2 flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-primary-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-500/30">
                            <span class="flex items-center border-r border-slate-200 bg-slate-100 px-3 text-sm font-semibold text-slate-600 select-none">+971</span>
                            <input type="tel" id="q-phone" name="phone" placeholder="Enter Phone" required
                                class="min-w-0 flex-1 bg-transparent px-3 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none">
                        </div>
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="phone"></p>
                    </div>

                    {{-- Designation --}}
                    <div>
                        <label for="q-designation" class="block text-sm font-semibold text-slate-700">Designation <span class="text-red-500">*</span></label>
                        <input type="text" id="q-designation" name="designation" placeholder="Enter Designation" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="designation"></p>
                    </div>

                    {{-- Company Name --}}
                    <div>
                        <label for="q-company" class="block text-sm font-semibold text-slate-700">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" id="q-company" name="company_name" placeholder="Enter Company Name" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="company_name"></p>
                    </div>

                    {{-- Company Size --}}
                    <div>
                        <label for="q-company-size" class="block text-sm font-semibold text-slate-700">Company Size <span class="text-red-500">*</span></label>
                        <select id="q-company-size" name="company_size" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                            <option value="">Select company size</option>
                            <option value="1-10">1 – 10 employees</option>
                            <option value="11-50">11 – 50 employees</option>
                            <option value="51-200">51 – 200 employees</option>
                            <option value="201-500">201 – 500 employees</option>
                            <option value="500+">500+ employees</option>
                        </select>
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="company_size"></p>
                    </div>

                    {{-- Math Captcha --}}
                    <div>
                        <label for="q-captcha" class="block text-sm font-semibold text-slate-700">
                            Verification: <span class="font-bold text-primary-600">7 + 3 = ?</span>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="q-captcha" name="captcha" placeholder="Your answer" required
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/30">
                        <p class="mt-1 hidden text-xs text-red-600 field-error" data-field="captcha"></p>
                    </div>

                </div>

                {{-- Submit --}}
                <div class="mt-7">
                    <button type="submit" id="quote-submit-btn"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-primary-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:cursor-not-allowed disabled:opacity-60">
                        <span id="quote-btn-text">Submit Free Quote Request</span>
                        <svg id="quote-btn-icon" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                        <svg id="quote-btn-spinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const modal      = document.getElementById('quote-modal');
    const backdrop   = document.getElementById('quote-modal-backdrop');
    const closeBtn   = document.getElementById('quote-modal-close');
    const openBtn    = document.getElementById('open-quote-modal');
    const heroPick   = document.getElementById('hero-service-pick');
    const serviceField = document.getElementById('quote-service-field');
    const serviceBadge = document.getElementById('quote-service-badge');
    const serviceLabel = document.getElementById('quote-service-label');
    const form       = document.getElementById('quote-form');
    const successBox = document.getElementById('quote-success');
    const errorBox   = document.getElementById('quote-error');
    const submitBtn  = document.getElementById('quote-submit-btn');
    const btnText    = document.getElementById('quote-btn-text');
    const btnIcon    = document.getElementById('quote-btn-icon');
    const btnSpinner = document.getElementById('quote-btn-spinner');

    function openModal() {
        const svc = heroPick ? heroPick.value : '';
        serviceField.value = svc;
        if (svc) {
            serviceLabel.textContent = svc;
            serviceBadge.classList.remove('hidden');
        } else {
            serviceBadge.classList.add('hidden');
        }
        successBox.classList.add('hidden');
        errorBox.classList.add('hidden');
        clearErrors();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function clearErrors() {
        document.querySelectorAll('#quote-form .field-error').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('#quote-form input, #quote-form select').forEach(el => {
            el.classList.remove('border-red-400', 'bg-red-50');
        });
    }

    function showFieldError(field, msg) {
        const errEl = document.querySelector(`#quote-form .field-error[data-field="${field}"]`);
        if (errEl) {
            errEl.textContent = msg;
            errEl.classList.remove('hidden');
        }
        const input = document.querySelector(`#quote-form [name="${field}"]`);
        if (input) input.classList.add('border-red-400');
    }

    if (openBtn)   openBtn.addEventListener('click', openModal);
    if (closeBtn)  closeBtn.addEventListener('click', closeModal);
    if (backdrop)  backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearErrors();
            successBox.classList.add('hidden');
            errorBox.classList.add('hidden');

            submitBtn.disabled = true;
            btnText.textContent = 'Sending…';
            btnIcon.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            const data = new FormData(form);

            try {
                const res = await fetch('{{ route("quote.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: data,
                });

                const json = await res.json();

                if (res.ok && json.success) {
                    successBox.textContent = json.message;
                    successBox.classList.remove('hidden');
                    form.reset();
                    serviceBadge.classList.add('hidden');
                } else if (res.status === 422 && json.errors) {
                    Object.entries(json.errors).forEach(([field, msgs]) => showFieldError(field, msgs[0]));
                    errorBox.textContent = 'Please fix the errors above and try again.';
                    errorBox.classList.remove('hidden');
                } else {
                    errorBox.textContent = 'Something went wrong. Please try again.';
                    errorBox.classList.remove('hidden');
                }
            } catch {
                errorBox.textContent = 'Network error. Please check your connection and try again.';
                errorBox.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                btnText.textContent = 'Submit Free Quote Request';
                btnIcon.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
            }
        });
    }
})();
</script>
