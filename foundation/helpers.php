<?php

/**
 * Generate an absolute URL to a given JS resource.
 */
if (!function_exists('traxMix')) {

    function traxMix($file)
    {
        return url('/').mix($file);
    }
}

/**
 * Get a class name given a route name.
 */
if (!function_exists('traxClassName')) {

    function traxClassName($routeName)
    {
        $className = ucwords($routeName, '.-');
        $className = str_replace('.', '\\', $className);
        $className = str_replace('-', '', $className);
        return $className;
    }
}

/**
 * Get a route name given a class name.
 */
if (! function_exists('traxRouteName')) {
    
    function traxRouteName($plugin, $className) {
        $sections = explode('\\', $className);
        $sections = array_map(function($section) {
            $section = preg_replace('/(?<=\\w)(?=[A-Z])/',"-$1", $section); 
            return strtolower($section);
        }, $sections);
        $routeName = implode('.', $sections).'s';
        $words = explode('-', $plugin);
        array_shift($words);
        $plugin = implode('-', $words);
        return $plugin.'.'.$routeName;
    }
}

/**
 * A function to check if we are in a Lumen context.
 */
if (! function_exists('traxRunningInLumen')) {
    
    function traxRunningInLumen() {
        return (strlen(app()->version()) > 7);
    }
}

/**
 * A function to generate an UUID.
 */
if (!function_exists('traxUuid')) {

    function traxUuid()
    {
        return \Faker\Factory::create()->uuid;
    }
}

/**
 * A function to generate the current datetime.
 */
if (!function_exists('traxNow')) {

    function traxNow()
    {
        return \Carbon\Carbon::now();
    }
}

/**
 * A function to generate the current datetime.
 */
if (!function_exists('traxNowString')) {

    function traxNowString()
    {
        return \Carbon\Carbon::now()->toDateTimeString();
    }
}

/**
 * Check if a header exists, including with the alternate syntax.
 */

if (! function_exists('traxHasHeader')) {
    
    function traxHasHeader($request, $header) {
        return (($request->has('method') && $request->has($header)) || $request->hasHeader($header));
    }
}

/**
 * Get a header, including with the alternate syntax.
 */

if (! function_exists('traxHeader')) {
    
    function traxHeader($request, $header) {
        if (!traxHasHeader($request, $header)) return null;
        return ($request->has('method') && $request->has($header)) ? $request->input($header) : $request->header($header);
    }
}

/**
 * Check if a content exists, including with the alternate syntax.
 */

if (! function_exists('traxHasContent')) {
    
    function traxHasContent($request) {
        return (($request->has('method') && $request->has('content')) || !empty($request->getContent()));
    }
}

/**
 * Get content, including with the alternate syntax.
 */

if (! function_exists('traxContent')) {
    
    function traxContent($request) {
        if (!traxHasContent($request)) return '';
        return ($request->has('method') && $request->has('content')) ? urldecode($request->input('content')) : $request->getContent();
    }
}

/**
 * Create a parent class for an exception handler.
 */
if (!function_exists('traxCreateExceptionHandlerSwitchClass')) {

    function traxCreateExceptionHandlerSwitchClass($namespace, $className)
    {
        $code = "namespace " . $namespace . "; ";
        if (traxRunningInLumen()) {
            $code .= "class " . $className . "ExceptionHandlerSwitch extends \Laravel\Lumen\Exceptions\Handler {}";
        } else {
            $code .= "class " . $className . "ExceptionHandlerSwitch extends \Illuminate\Foundation\Exceptions\Handler {}";
        }
        eval($code);
    }
}


