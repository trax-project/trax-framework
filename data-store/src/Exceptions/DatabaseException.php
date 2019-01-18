<?php

namespace Trax\DataStore\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class DatabaseException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessage = 'Database Exception';

    /**
     * HTTP code
     */
    protected $httpCode = 500;


}
