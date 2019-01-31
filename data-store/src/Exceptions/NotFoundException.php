<?php

namespace Trax\DataStore\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class NotFoundException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessage = 'Not Found Exception';

    /**
     * HTTP code
     */
    protected $httpCode = 404;


}
