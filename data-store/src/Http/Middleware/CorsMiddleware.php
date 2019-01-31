<?php

namespace Trax\DataStore\Http\Middleware;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, $next, $guard = null)
    {
        // Fine, we continue
        $response =  $next($request);

        // Define allowed origin
        if (!$request->hasHeader('Origin')) $origin = config('app.url');
        else $origin = $request->header('Origin');

        if (get_class($response) == 'Illuminate\Http\Response') {

            $response->header('Access-Control-Allow-Origin', $origin);
            $response->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Experience-API-Version');
            $response->header('Access-Control-Allow-Credentials', 'true');

        } else if (get_class($response) == 'Symfony\Component\HttpFoundation\StreamedResponse') {

            // Used to download files
            
        }

        return $response;
    }


}
