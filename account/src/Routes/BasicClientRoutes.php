<?php

namespace Trax\Account\Routes;

use Trax\DataStore\Routes\DataRoutes;

class BasicClientRoutes extends DataRoutes
{
    /**
     * The data model.
     */
    protected $model = 'BasicClient';

    /**
     * Only these API methods. May be overridden.
     */
    protected $only = array('store', 'get', 'find', 'update', 'delete');


}
