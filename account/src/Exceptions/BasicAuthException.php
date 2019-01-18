<?php

namespace Trax\Account\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class BasicAuthException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessage = 'Basic Auth Exception';

    /**
     * HTTP code
     */
    protected $httpCode = 401;


}
