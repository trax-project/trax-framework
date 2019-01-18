<?php

namespace Trax\DataStore\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class RequestException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessage = 'Request Exception';

}
