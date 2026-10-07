<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request and set app locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?? session('locale')
            ?? $request->cookie('locale')
            ?? 'vi';

        if (! in_array($locale, ['vi', 'en'], true)) {
            $locale = 'vi';
        }

        if ($request->has('lang')) {
            session(['locale' => $locale]);
            cookie()->queue(cookie()->forever('locale', $locale));
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
