<?php

namespace Trax\Foundation;

use Illuminate\Support\Facades\Facade;

class TraxFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\Foundation\TraxServices';
    }

}
