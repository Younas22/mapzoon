<section id="pos-billing" class="relative overflow-hidden bg-black py-16 lg:py-20">
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary-300 to-transparent" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2 lg:gap-12">

            <div class="relative mx-auto w-full max-w-lg reveal reveal-delay-2 lg:order-1" aria-hidden="true">
                <div class="absolute -right-12 -top-10 h-56 w-56 rounded-full bg-primary-300/30 blur-3xl animate-blob"></div>
                <div class="absolute -left-12 bottom-0 h-56 w-56 rounded-full bg-primary-200/30 blur-3xl animate-blob" style="animation-delay:-7s"></div>

                <div class="absolute -right-4 bottom-6 z-20 hidden items-center gap-3 rounded-2xl px-4 py-3 shadow-xl glass animate-float-slow sm:flex">
                    <svg class="h-5 w-5 text-primary-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 17l5-5 4 4 8-9" />
                        <path d="M14 7h6v6" />
                    </svg>
                    <span class="text-sm font-semibold text-slate-800">+18% Sales This Week</span>
                </div>

                <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl ring-1 ring-white/10 transition duration-500 ease-out hover:ring-primary-500/50">
                    <img src="{{ asset('uploads/module/POS.png') }}" alt="MapZoon POS dashboard with connected receipt printer" class="block w-full h-auto" loading="lazy">
                </div>
            </div>

            <div class="reveal lg:order-2">
                <p class="text-sm font-semibold uppercase tracking-wider text-primary-400">POS &amp; Billing System</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                    Run Your Business Operations Like a Modern SaaS Platform
                </h2>
                <p class="mt-3 text-base text-slate-300 sm:text-lg">
                    Sales, invoices &amp; inventory — all from one clean dashboard.
                </p>

                <ul class="reveal reveal-delay-3 mt-6 flex flex-wrap gap-3">
                    <li class="glass-dark rounded-full px-5 py-2.5 text-sm font-semibold text-white">Invoices</li>
                    <li class="glass-dark rounded-full px-5 py-2.5 text-sm font-semibold text-white">Inventory</li>
                    <li class="glass-dark rounded-full px-5 py-2.5 text-sm font-semibold text-white">Sales Reports</li>
                    <li class="glass-dark rounded-full px-5 py-2.5 text-sm font-semibold text-white">Customer Management</li>
                    <li class="glass-dark rounded-full px-5 py-2.5 text-sm font-semibold text-white">WhatsApp Sharing</li>
                    <li class="glass-dark rounded-full px-5 py-2.5 text-sm font-semibold text-white">Expense Tracking</li>
                </ul>

                <div class="reveal mt-8">
                    <a href="{{ route('contact.page') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-primary-500 px-7 py-3.5 text-base font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:-translate-y-0.5 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        Request a POS Demo
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
