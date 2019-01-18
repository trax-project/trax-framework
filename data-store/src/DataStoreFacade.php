<?php

namespace Trax\DataStore;

use Illuminate\Support\Facades\Facade;

class DataStoreFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\DataStore\MainDataStoreServices';
    }

}
