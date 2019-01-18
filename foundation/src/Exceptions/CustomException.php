<?php

namespace Trax\Foundation\Exceptions;

use Exception;

class CustomException extends Exception
{
    /**
     * Default message
     */
    protected $defaultMessage = 'Custom Exception';

    /**
     * HTTP code
     */
    protected $httpCode = 400;

    /**
     * HTTP headers
     */
    protected $httpHeaders = [];
    
    
    /**
     * Create a new exception.
     */
    public function __construct($message = null, $httpCode = null, $httpHeaders = null)
    {
        // Message
        if (!isset($message)) {
            $message = isset($this->defaultMessageKey) ? __($this->defaultMessageKey) : $this->defaultMessage;
        }
        
        // HTTP Code
        if (isset($httpCode)) $this->httpCode = $httpCode;
        
        // HTTP headers
        if (isset($httpHeaders)) $this->httpHeaders = array_merge($httpHeaders, $this->httpHeaders);
        
        parent::__construct($message);
    }

    /**
     * Get HTTP code.
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Get the original exception.
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }


}
