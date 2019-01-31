<?php

namespace Trax\Notification;

use Illuminate\Support\Facades\Facade;

class NotificationFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\Notification\NotificationServices';
    }

}
