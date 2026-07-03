<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/', function () {
    return view('landing', [
        'canonical' => url('/'),
        'keywords' => 'Local SEO USA, Google Maps SEO, Google Business Profile Optimization, Google Business Profile Management, Local SEO Services, Website Development, Small Business SEO, Local Search Marketing, Google Maps Ranking, Local Business Marketing, SEO Agency USA',
        'ogTitle' => 'Local SEO Services for USA Businesses | MapZoon',
        'ogDescription' => 'Improve your Google Maps rankings, attract more local customers, and grow your business with Local SEO, Google Business Profile optimization, and professional website development.',
        'twitterTitle' => 'Local SEO Services for USA Businesses | MapZoon',
        'twitterDescription' => 'Helping local businesses across the USA grow through Local SEO, Google Maps optimization, and high-converting websites.',
    ]);
})->name('home');

Route::get('/services', function () {
    return view('pages.services', [
        'canonical' => url('/services'),
        'ogTitle' => 'Local SEO Services | MapZoon',
        'ogDescription' => 'Discover Local SEO, Google Business Profile optimization, website development, and POS solutions for growing local businesses.',
        'twitterTitle' => 'Local SEO Services | MapZoon',
        'twitterDescription' => 'Helping local businesses grow with Local SEO, Google Maps optimization, websites, and POS solutions.',
    ]);
})->name('services');

Route::get('/process', function () {
    return view('pages.process', [
        'canonical' => url('/process'),
        'ogTitle' => 'Our Local SEO Process | MapZoon',
        'ogDescription' => 'Discover the step-by-step Local SEO process we use to help businesses improve visibility, rankings, and local customer growth.',
        'twitterTitle' => 'Our Local SEO Process | MapZoon',
        'twitterDescription' => 'See how our proven Local SEO process helps businesses grow through Google Maps and local search.',
    ]);
})->name('process');

Route::get('/website', function () {
    return view('pages.website', [
        'canonical' => url('/website'),
        'ogTitle' => 'Website Development Services | MapZoon',
        'ogDescription' => 'Get a fast, mobile-friendly, SEO-ready website designed to attract more customers and grow your business.',
        'twitterTitle' => 'Website Development Services | MapZoon',
        'twitterDescription' => 'Professional websites built for performance, SEO, and lead generation.',
    ]);
})->name('website');

Route::get('/pos-system', function () {
    return view('pages.pos-system', [
        'canonical' => url('/pos-system'),
        'ogTitle' => 'POS & Billing System | MapZoon',
        'ogDescription' => 'Simplify your business operations with our cloud-based POS & Billing System for sales, inventory, invoicing, and customer management.',
        'twitterTitle' => 'POS & Billing System | MapZoon',
        'twitterDescription' => 'A complete POS & Billing solution for managing sales, inventory, invoices, and customers.',
    ]);
})->name('pos-system');

Route::get('/testimonials', function () {
    return view('pages.testimonials', [
        'canonical' => url('/testimonials'),
        'ogTitle' => 'Client Testimonials | MapZoon',
        'ogDescription' => 'See what local businesses say about working with MapZoon and our Local SEO services.',
        'twitterTitle' => 'Client Testimonials | MapZoon',
        'twitterDescription' => 'Read reviews and success stories from businesses that partnered with MapZoon.',
    ]);
})->name('testimonials');

Route::get('/faq', function () {
    return view('pages.faq', [
        'canonical' => url('/faq'),
        'ogTitle' => 'Frequently Asked Questions | MapZoon',
        'ogDescription' => 'Get answers to frequently asked questions about Local SEO, Google Maps optimization, websites, and business growth services.',
        'twitterTitle' => 'Local SEO FAQ | MapZoon',
        'twitterDescription' => 'Answers to common questions about Local SEO, Google Business Profile optimization, websites, and POS solutions.',
    ]);
})->name('faq');

// Company section pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/why-choose-us', function () {
    return view('pages.why-choose-us', [
        'canonical' => url('/why-choose-us'),
        'ogTitle' => 'Why Choose MapZoon | Local SEO Experts',
        'ogDescription' => 'Learn what makes MapZoon the trusted partner for Local SEO, Google Maps optimization, website development, and business growth.',
        'twitterTitle' => 'Why Choose MapZoon',
        'twitterDescription' => 'See why businesses trust MapZoon for Local SEO and Google Business Profile optimization.',
    ]);
})->name('why-choose-us');

Route::get('/team', function () {
    return view('pages.team', [
        'canonical' => url('/team'),
        'ogDescription' => 'Meet the experts behind MapZoon helping local businesses grow through Local SEO and Google Maps optimization.',
        'twitterTitle' => 'Meet the MapZoon Team',
        'twitterDescription' => 'Meet the Local SEO experts behind MapZoon.',
    ]);
})->name('team');

Route::get('/contact', function () {
    return view('pages.contact', [
        'canonical' => url('/contact'),
        'ogTitle' => 'Contact MapZoon | Local SEO Experts',
        'ogDescription' => 'Get in touch with MapZoon to discuss your Local SEO, Google Maps optimization, website, or digital marketing needs.',
        'twitterTitle' => 'Contact MapZoon',
        'twitterDescription' => 'Contact our Local SEO experts and request your free consultation today.',
    ]);
})->name('contact.page');

Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies');
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show'])->name('case-studies.show');

// Other top-level pages
Route::get('/pricing', function () {
    return view('pages.pricing');
})->name('pricing');

Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::post('/jobs', [JobsController::class, 'store'])->name('jobs.apply');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Blog post detail — kept last so it doesn't shadow the static routes above.
Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
