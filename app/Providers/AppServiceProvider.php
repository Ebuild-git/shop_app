<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

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
        // Paginator::useBootstrap();
        // $locale = Cookie::get('locale', Session::get('locale', config('app.locale')));
        // App::setLocale($locale);
        Paginator::useBootstrap();

        $allowedLocales = ['en', 'fr', 'ar'];

        $locale = Cookie::get('locale')
            ?? Session::get('locale')
            ?? config('app.locale');

        if (! in_array($locale, $allowedLocales)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
    }
}
