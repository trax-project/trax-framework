<?php

namespace Trax\UI\Http\Middleware;

use Auth;
use App;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, $next, $guard = null) 
    {
        // Set locale
        if ($user = Auth::user()) {

            // Apply user language
            $lang = $user->lang;

        } else if ($request->has('lang') && ($request->input('lang') == 'en' || $request->input('lang') == 'fr')) {

            // Set the language specified in the URL
            $lang = $request->input('lang');
            session(['lang' => $lang]);

        } else {

            // Get current language from session coockie
            $lang = session('lang', config('app.locale'));
        }
        App::setLocale($lang);

        // Fine, we continue
        return $next($request);
    }

}

