<?php

namespace Trax\Notification\Routes;

use Trax\DataStore\Routes\DataRoutes;

class NotificationRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'Notification';

    /**
     * No AJAX access.
     */
    protected $methods = array();


}
