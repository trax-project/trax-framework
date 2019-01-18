<?php

namespace Trax\Account\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;

use Trax\Foundation\Exceptions\TraxExceptionHandler;

class AccountExceptionHandler extends TraxExceptionHandler
{

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ForbiddenException) {
            if ($request->expectsJson()) {
                return response($exception->getMessage(), $exception->getHttpCode());
            }
            abort(403);
        }
        return parent::render($request, $exception);
    }


    /**
     * Convert an authentication exception into an unauthenticated response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest(route('login'));
    }
}
