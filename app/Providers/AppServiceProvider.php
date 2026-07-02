<?php

namespace App\Providers;

use App\Models\Country;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(fn () => route('admin.dashboard'));

        View::composer('partials.landing.quote-modal', function ($view) {
            $view->with('countries', Country::orderBy('name')->get(['id', 'name', 'iso2', 'phone_code']));
        });
    }
}
