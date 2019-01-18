<?php

namespace Trax\Account\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class ForbiddenException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessageKey = 'trax-account::common.not_allowed';

    /**
     * HTTP code
     */
    protected $httpCode = 403;


}
