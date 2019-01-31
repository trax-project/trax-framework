<?php

namespace Trax\Account;

use Illuminate\Support\Facades\Facade;

class AccountFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\Account\AccountServices';
    }

}
