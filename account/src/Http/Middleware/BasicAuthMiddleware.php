<?php

namespace Trax\Account\Http\Middleware;

use Trax\Account\Exceptions\BasicAuthException;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, $next, $guard = null) 
    {
        // Check if credentials are provided
        if (!traxHasHeader($request, 'Authorization')) throw new BasicAuthException('Missing Authorization header.');
        
        // Get credentials
        $authorization = traxHeader($request, 'Authorization');
        try {
            list($basic, $auth) = explode(' ', $authorization);
            if ($basic != 'Basic') throw new BasicAuthException('Invalid BasicHTTP header.');
            list($username, $password) = explode(':', base64_decode(trim($auth)));
        } catch(\Exception $e) {
            throw new BasicAuthException('Invalid BasicHTTP header.');
        }

        // Check if they are empty
        if (empty($username)) throw new BasicAuthException('Empty BasicHTTP username.');
        
        // Get the basic account
        try {
            $client = app('Trax\Account\AccountServices')->basicClients()->findBy('username', $username);
        } catch (\Exception $e) {            
            throw new BasicAuthException('Unknown username ('.$username.')');
        }

        // Check password
        if ($password !== $client->password) throw new BasicAuthException('Invalid password.');

        // Fine, we continue
        return $next($request);
    }

}

