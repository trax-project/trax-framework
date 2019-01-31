<?php

namespace Trax\Foundation\Exceptions;

use Exception;

traxCreateExceptionHandlerSwitchClass('Trax\Foundation\Exceptions', 'Trax');

class TraxExceptionHandler extends TraxExceptionHandlerSwitch
{

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Exception $exception)
    {
        // Custom exceptions
        if ($exception instanceof CustomException)
            return response($exception->getMessage(), $exception->getHttpCode())->withHeaders($exception->getHttpHeaders());
        
        return parent::render($request, $exception);
    }

}
