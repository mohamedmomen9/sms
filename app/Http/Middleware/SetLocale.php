<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('localization.locales', ['en', 'ar']);
        $defaultLocale = config('localization.default_locale', 'en');
        
        // Check for locale in session, fallback to browser preference, then default
        $locale = Session::get('locale');
        
        if (!$locale) {
            // Try to detect from browser
            $browserLocales = $request->getLanguages();
            
            foreach ($browserLocales as $browserLocale) {
                $shortLocale = substr($browserLocale, 0, 2);
                if (in_array($shortLocale, $supportedLocales)) {
                    $locale = $shortLocale;
                    break;
                }
            }
        }
        
        // Default if not found
        $locale = $locale ?? $defaultLocale;
        
        // Validate locale
        if (!in_array($locale, $supportedLocales)) {
            $locale = $defaultLocale;
        }
        
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
}
