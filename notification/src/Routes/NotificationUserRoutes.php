<?php

namespace Trax\Notification\Routes;

use Trax\DataStore\Routes\DataRoutes;

class NotificationUserRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'NotificationUser';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('get', 'update', 'delete');


}
