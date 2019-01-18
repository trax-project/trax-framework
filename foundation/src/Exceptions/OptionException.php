<?php

namespace Trax\Foundation\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class OptionNotFoundException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessage = 'Option not found';

    /**
     * HTTP code
     */
    protected $httpCode = 404;


}
