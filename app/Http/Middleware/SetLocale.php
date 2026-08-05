<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    { 
        // Check if the 'lang' parameter exists in the route
        $lang = $request->route('lang');

        // Validate the language code
        if ($lang && in_array($lang, ['en', 'ar', 'tr'])) {
            app()->setLocale($lang);
            session(['locale' => $lang]);
        } 
        // Fallback to session if no URL parameter is provided
        else{
            $locale = session('locale', 'en');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}