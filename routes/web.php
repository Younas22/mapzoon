<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/services', function () {
    return view('pages.services');
})->name('services');

Route::get('/process', function () {
    return view('pages.process');
})->name('process');

Route::get('/website', function () {
    return view('pages.website');
})->name('website');

Route::get('/pos-system', function () {
    return view('pages.pos-system');
})->name('pos-system');

Route::get('/testimonials', function () {
    return view('pages.testimonials');
})->name('testimonials');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

// Company section pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/why-choose-us', function () {
    return view('pages.why-choose-us');
})->name('why-choose-us');

Route::get('/team', function () {
    return view('pages.team');
})->name('team');

Route::get('/contact', function () {
    return view('pages.contact');
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
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
