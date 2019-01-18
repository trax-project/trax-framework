<?php

namespace Trax\UI;

use Illuminate\Support\Facades\Facade;

class UIFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\UI\UIServices';
    }
}
