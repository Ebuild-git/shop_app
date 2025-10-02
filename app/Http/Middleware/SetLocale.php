<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.supported_locales', ['fr', 'en']);
        $fallback  = config('app.fallback_locale', 'fr');

        $locale = session('locale', config('app.locale'));

        if (!in_array($locale, $supported)) {
            $locale = $fallback;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
