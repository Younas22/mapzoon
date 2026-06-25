<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
